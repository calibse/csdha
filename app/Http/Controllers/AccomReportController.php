<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AccomReport;
use App\Http\Requests\UpdateAccomReportStatusRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\PagedView;
use WeasyPrint\Facade as WeasyPrint;
use App\Models\User;
use App\Models\EventDate;
use App\Events\AccomReportStatusChanged;
use App\Models\Gpoa;
use App\Services\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateAccomReportBackgroundRequest;
use App\Http\Requests\GenerateAccomReportRequest;

class AccomReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.index:viewAnyAccomReport,' . Event::class,
                only: [
                'index', 'editBackground', 'updateBackground'
            ]),
            new Middleware('auth.accom-report:viewAccomReport,event', 
                only: ['show']),
            new Middleware('auth.accom-report:submitAccomReport,event', 
                only: [
                'submit', 'prepareForSubmit'
            ]),
            new Middleware('auth.accom-report:returnAccomReport,event', 
                only: [
                'return', 'prepareForReturn'
            ]),
            new Middleware('auth.accom-report:approveAccomReport,event', 
                only: [
                'approve', 'prepareForApprove'
            ]),
            new Middleware('auth.index:genAccomReport,' . Event::class, 
                only: [
                'generate', 'stream'
            ]),
        ];
    }
    public function index()
    {
        $position = auth()->user()->position_name;
        if (!in_array($position, ['adviser', 'president', null])) {
            $position = 'officers';
        }
        switch ($position) {
        case 'officers':
            $accomReports = AccomReport::whereNot('status', 'pending');
            break;
        case 'president':
            $accomReports = AccomReport::forPresident();
            break;
        case 'adviser':
            $accomReports = AccomReport::forAdviser();
            break;
        }
        $accomReports = $accomReports->active()->orderBy("updated_at", "desc")
            ->paginate("7");
        $gpoa = Gpoa::active()->exists();
        return view('accom-reports.index', [
            'gpoa' => $gpoa,
            'accomReports' => $accomReports,
            'genRoute' => route('accom-reports.generate')
        ]);
    }

    public function show(Request $request, Event $event)
    {
        $accomReport = $event->accomReport;
        $date = null;
        $fileRoute = null;
        if ($accomReport) {
            $status = $accomReport->status;
            $date = match ($status) {
                'draft' => $accomReport->created_at,
                'pending' => $accomReport->submitted_at,
                'returned' => $accomReport->returned_at,
                'approved' => $accomReport->approved_at
            };
            $date = $date->timezone(config('timezone'))
                ->format(config('app.date_format'));
        }
        switch(auth()->user()->position_name) {
        case 'president':
            $actions = [
                'submit' => false,
                'return' => true,
                'approve' => true,
            ];
            break;
        case 'adviser':
            $actions = [
                'submit' => false,
                'return' => false,
                'approve' => false,
            ];
            break;
        default:
            $actions = [
                'submit' => true,
                'return' => false,
                'approve' => false,
            ];
        }
        $backRoute = $request->from === 'events'
            ? route('events.show', ['event' => $event->public_id])
            : route('accom-reports.index');
        if ($accomReport->filepath) {
            $fileRoute = route('events.accom-report.stream', [
                'event' => $event->public_id
            ]);
        }
        return view('accom-reports.show', [
            'actions' => $actions,
            'accomReport' => $accomReport,
            'event' => $event,
            'date' => $date,
            'backRoute' => $backRoute,
            'fileRoute' => $fileRoute,
            'submitRoute' => route('accom-reports.prepareForSubmit', [
                'event' => $event->public_id
            ]),
            'returnRoute' => route('accom-reports.prepareForReturn', [
                'event' => $event->public_id
            ]),
            'approveRoute' => route('accom-reports.prepareForApprove', [
                'event' => $event->public_id
            ]),
            'editRoute' => route('events.edit', [
                'event' => $event->public_id,
                'from' => 'accom-reports',
                'accom_reports_from' => $request->from
            ]),
            'changeBgRoute' => route('accom-reports.background.edit', [
                'from' => $event->public_id
            ])
        ]);
    }

    public function prepareForSubmit(Event $event)
    {
        return view('accom-reports.prepare', [
            'action' => 'Submit',
            'formAction' => route('accom-reports.submit', [
                'event' => $event->public_id
            ]),
            'backRoute' => route('accom-reports.show', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function prepareForReturn(Event $event)
    {
        $accomReport = $event->accomReport;
        if (!$accomReport) {
            abort(404);
        }
        return view('accom-reports.prepare', [
            'action' => 'Return',
            'formAction' => route('accom-reports.return', [
                'event' => $event->public_id
            ]),
            'backRoute' => route('accom-reports.show', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function prepareForApprove(Event $event)
    {
        $accomReport = $event->accomReport;
        if (!$accomReport) {
            abort(404);
        }
        return view('accom-reports.prepare', [
            'action' => 'Approve',
            'formAction' => route('accom-reports.approve', [
                'event' => $event->public_id
            ]),
            'backRoute' => route('accom-reports.show', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function submit(UpdateAccomReportStatusRequest $request,
            Event $event)
    {
        $accomReport = $event->accomReport;
        if (!$accomReport) {
            $accomReport = new AccomReport;
            $accomReport->event()->associate($event);
        }
        $accomReport->current_step = 'president';
        $accomReport->status = 'pending';
        $accomReport->submitted_at = now();
        $accomReport->comments = $request->comments ?? null;
        $accomReport->save();
        AccomReportStatusChanged::dispatch($accomReport);
        return redirect()->route('accom-reports.show', [
            'event' => $event->public_id
        ])->with('status', 'Accomplishment report submitted.');
    }

    public function return(UpdateAccomReportStatusRequest $request,
            Event $event)
    {
        $accomReport = $event->accomReport;
        if (!$accomReport) {
            abort(404);
        }
        $accomReport->current_step = 'officers';
        $accomReport->status = 'returned';
        $accomReport->returned_at = now();
        $accomReport->comments = $request->comments ?? null;
        $accomReport->save();
        AccomReportStatusChanged::dispatch($accomReport);
        return redirect()->route('accom-reports.index')->with('status',
            'Accomplishment report returned.');
    }

    public function approve(UpdateAccomReportStatusRequest $request,
            Event $event)
    {
        $accomReport = $event->accomReport;
        if (!$accomReport) {
            abort(404);
        }
        $accomReport->current_step = 'adviser';
        $accomReport->status = 'approved';
        $accomReport->approved_at = now();
        $accomReport->comments = $request->comments ?? null;
        $accomReport->save();
        AccomReportStatusChanged::dispatch($accomReport);
        return redirect()->route('accom-reports.index')->with('status',
            'Accomplishment report approved.');
    }

    public function generate(GenerateAccomReportRequest $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $start = false;
        $empty = true;
        $fileRoute = null;
        if ($startDate) {
            $start = true;
            $events = Event::active()->approved($startDate, $endDate)->exists();
            $fileRoute = $events ? route('accom-reports.stream', [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]) : null;
            $empty = $events ? false : true;
        } elseif (session('errors')?->any()) {
	    $empty = false;
	    $start = true;
        } elseif (Event::active()->approved()->exists()) {
            $empty = false;
            $start = true;
            $startDate = $startDate ?? EventDate::active()->approved()
                ->orderBy('date', 'asc')->value('date')->toDateString();
            $endDate = $endDate ?? EventDate::active()->approved()
                ->orderBy('date', 'desc')->value('date')->toDateString();
        }
        return view('accom-reports.gen-accom-report', [
            'backRoute' => route('accom-reports.index'),
            'fileRoute' => $fileRoute,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'start' => $start,
            'empty' => $empty
        ]);
    }

    public function stream(Request $request)
    {
        $gpoa = Gpoa::active()->get();
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        if (!$startDate) abort(404);
        $allEvents = $gpoa->events()->approved($startDate, $endDate)->exists();
        if (!$allEvents) abort(404);
        return WeasyPrint::prepareSource(new PagedView('events.accom-report', 
            $gpoa->accomReportViewData($startDate, $endDate)))
            ->stream('accom_report_set.pdf');
    }

    public function editBackground(Request $request)
    {
        $backRoute = $request->from 
            ? route('accom-reports.show', [
                'event' => $request->from
            ])
            : route('accom-reports.index');
        return view('accom-reports.edit-background', [
            'backRoute' => $backRoute,
            'formAction' => route('accom-reports.background.update'),
            'from' => $request->from
        ]);
    }

    public function updateBackground(UpdateAccomReportBackgroundRequest 
        $request)
    {
        $imageFile = 'accom_reports/background.jpg';
        if ($request->boolean('remove_background')) {
            Storage::delete($imageFile);
            if ($request->from) {
                return redirect()->route('accom-reports.show', [
                    'event' => $request->from
                ]);
            }
            return redirect()->route('accom-reports.index')->with('status', 
                'AR background changed.');
        }
        $image = new Image($request->file('background_file'));
        Storage::put($imageFile, (string)$image->get());
        if ($request->from) {
            return redirect()->route('accom-reports.show', [
                'event' => $request->from
            ]);
        }
        return redirect()->route('accom-reports.index')->with('status', 
            'AR background changed.');
    }
}
