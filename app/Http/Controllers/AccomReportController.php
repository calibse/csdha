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
use App\Services\Format;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateAccomReportBackgroundRequest;
use App\Http\Requests\GenerateAccomReportRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Jobs\GenerateAccomReport;
use App\Jobs\MakeAccomReport;
use App\Events\EventUpdated;
use Illuminate\Support\Carbon;

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
            new Middleware('auth.index:updateAccomReportBG,' . Event::class, 
                only: [
                'editBackground', 'updateBackground'
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
            $accomReports = AccomReport::whereNot('status', 'draft');
            break;
        case 'president':
            $accomReports = AccomReport::forPresident();
            break;
        case 'adviser':
            $accomReports = AccomReport::forAdviser();
            break;
        }
        $accomReports = $accomReports->active()->orderBy('updated_at', 'desc')
            ->paginate(15);
        $gpoa = Gpoa::active()->exists();
        return view('accom-reports.index', [
            'gpoa' => $gpoa,
            'accomReports' => $accomReports,
            'genRoute' => route('accom-reports.generate'),
            'changeBgRoute' => route('accom-reports.background.edit'),
            'updateBgRoute' => route('accom-reports.background.update'),
        ]);
    }

    public function show(Request $request, Event $event)
    {
        $accomReport = $event->accomReport;
        if (!$accomReport) {
            $accomReport = new AccomReport;
            $accomReport->event()->associate($event);
            $accomReport->status = 'draft';
            $accomReport->current_step = 'officers';
            $accomReport->save();
        }
        $date = null;
        $fileRoute = null;
        if ($accomReport) {
            $status = $accomReport->status;
            $date = match ($status) {
                'draft' => null,
                'pending' => $accomReport->submitted_at,
                'returned' => $accomReport->returned_at,
                'approved' => $accomReport->approved_at
            };
            $date = $date?->timezone(config('timezone'))
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
        $backRoute = route('accom-reports.index');
        if ($accomReport->filepath) {
            $id = $accomReport->file_updated_at?->format('ymdHis') ?? 
                $accomReport->updated_at->format('ymdHis');
            $fileRoute = route('events.accom-report.stream', [
                'event' => $event->public_id,
                'id' => $id,
            ], false);
        }
        $prepareMessage = Format::documentPrepareMessage();
        $updateMessage = Format::documentUpdateMessage();
        $updated = $accomReport->file_updated;
        $submitActionRoute = route('accom-reports.submit', [
            'event' => $event->public_id
        ]);
        $returnActionRoute = route('accom-reports.return', [
            'event' => $event->public_id
        ]);
        $approveActionRoute = route('accom-reports.approve', [
            'event' => $event->public_id
        ]);
        $formActionUrl = session('form_action_url');
        $action = null;
        if ($formActionUrl) {
            switch ($formActionUrl) {
            case $returnActionRoute:
                $action = 'Return'; break;
            case $submitActionRoute:
                $action = 'Submit'; break;
            case $approveActionRoute:
                $action = 'Approve'; break;
            }
        }
        $response = response()->view('accom-reports.show', [
            'actions' => $actions,
            'accomReport' => $accomReport,
            'event' => $event,
            'date' => $date,
            'backRoute' => $backRoute,
            'fileRoute' => $fileRoute,
            'submitRoute' => route('accom-reports.prepareForSubmit', [
                'event' => $event->public_id
            ]),
            'submitActionRoute' => $submitActionRoute,
            'returnRoute' => route('accom-reports.prepareForReturn', [
                'event' => $event->public_id
            ]),
            'returnActionRoute' => $returnActionRoute,
            'approveRoute' => route('accom-reports.prepareForApprove', [
                'event' => $event->public_id
            ]),
            'approveActionRoute' => $approveActionRoute,
            'eventRoute' => route('events.show', [
                'event' => $event->public_id,
            ]),
            'updated' => $updated,
            'updateMessage' => $updateMessage,
            'prepareMessage' => $prepareMessage,
            'formActionUrl' => $formActionUrl,
            'action' => $action,
        ]);
        if (!auth()->user()->can('makeAccomReport', $event)) {
            return $response;
        }
        $gpoa = Gpoa::active()->first();
        MakeAccomReport::dispatch($gpoa, $event, auth()->user())
            ->onQueue('pdf');
        return $response->header('Refresh', '5');
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
        $hasInput = false;
        $fileRoute = null;
        $jobCache = 'gen_accom_reports';
        $jobs = Cache::get($jobCache, []);
        $hasLastJob = $userJob = $jobs[auth()->user()->id] ?? [];
        $jobDone = $hasLastJob ? $hasLastJob['finished'] : false;
        $hasApproved = Event::active()->approved()->exists();
        if ($hasLastJob && $jobDone) {
            $startDate = $userJob['start_date'];
            $endDate = $userJob['end_date'];
            Cache::lock($jobCache . '_lock', 2)->block(1, function () 
                use ($jobCache) {
                $jobs = Cache::get($jobCache, []);
                unset($jobs[auth()->user()->id]);
                Cache::put($jobCache, $jobs);
            });
            $fileRoute = route('accom-reports.stream', [
                'id' => now()->format('ymdHis')
            ], false);
        } elseif ($hasLastJob && !$jobDone) {
            $startDate = $userJob['start_date'];
            $endDate = $userJob['end_date'];
        } elseif (!$startDate && $hasApproved) {
            $startDate = $startDate ?? EventDate::active()->approved()
                ->orderBy('date', 'asc')->value('date')?->toDateString();
            $endDate = $endDate ?? EventDate::active()->approved()
                ->orderBy('date', 'desc')->value('date')?->toDateString();
            if ($startDate === $endDate) {
                $endDate = Carbon::parse($endDate)?->addDay()->toDateString();
            }
	} elseif ((!$hasLastJob || $jobDone) && $hasApproved) {
            $hasInput = true;
            $events = Event::active()->approved($startDate, $endDate)->exists();
            $requestId = Str::random(8);
            $userJob = [
                'request_id' => $requestId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'finished' => false,
            ];
            if ($events) {
                Cache::lock($jobCache . '_lock', 2)->block(1, function () 
                    use ($jobCache, $userJob) {
                    $jobs = Cache::get($jobCache, []);
                    $jobs[auth()->user()->id] = $userJob;
                    Cache::put($jobCache, $jobs);
                });
                $gpoa = Gpoa::active()->first();
                GenerateAccomReport::dispatch($gpoa, auth()->user(), 
                    $requestId, $startDate, $endDate)->onQueue('pdf');
                $hasLastJob = true;
                $jobDone = false;
            }
        } 
        $prepareMessage = Format::documentPrepareMessage();
        $response = response()->view('accom-reports.gen-accom-report', [
            'backRoute' => route('accom-reports.index'),
            'fileRoute' => $fileRoute,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'hasApproved' => $hasApproved,
            'hasInput' => $hasInput,
            'hasLastJob' => $hasLastJob,
            'jobDone' => $jobDone,
            'prepareMessage' => $prepareMessage,
            'cancelFormAction' => route('accom-reports.stop-generating'),
        ]);

        if (session('errors')?->any() || !$hasLastJob || $jobDone) {
            return $response;
        }
        return $response->header('Refresh', '5');
    }

    public function stopGenerating()
    {
        $jobCache = 'gen_accom_reports';
        Cache::lock($jobCache . '_lock', 2)->block(1, function () 
            use ($jobCache) {
            $jobs = Cache::get($jobCache, []);
            unset($jobs[auth()->user()->id]);
            Cache::put($jobCache, $jobs);
        });
        return redirect()->route('accom-reports.generate');
    }

    public function stream(Request $request)
    {
        $jobCache = 'gen_accom_reports';
        $jobs = Cache::get($jobCache, []);
        $hasLastJob = $jobs[auth()->user()->id] ?? [];
        $jobDone = $hasLastJob ? $hasLastJob['finished'] : false;
        if ($hasLastJob && !$jobDone) abort(404);
        $user = auth()->user();
        $file = "gen_accom_reports/accom_report_{$user->id}.pdf";
        return response()->file(Storage::path($file));
        /*
        $gpoa = Gpoa::active()->first();
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        if (!$startDate) abort(404);
        $allEvents = $gpoa->events()->approved($startDate, $endDate)->exists();
        if (!$allEvents) abort(404);
        return WeasyPrint::prepareSource(new PagedView('events.accom-report', 
            $gpoa->accomReportViewData($startDate, $endDate)))
            ->stream('accom_report_set.pdf');
        */
    }

    public function editBackground(Request $request)
    {
        $backRoute = route('accom-reports.index');
        return view('accom-reports.edit-background', [
            'backRoute' => $backRoute,
            'formAction' => route('accom-reports.background.update'),
        ]);
    }

    public function updateBackground(UpdateAccomReportBackgroundRequest 
        $request)
    {
        $gpoa = Gpoa::active()->first();
        $imageFile = 'accom_reports/background.jpg';
        if ($request->boolean('remove_background')) {
            Storage::delete($imageFile);
            foreach ($gpoa->events as $event) {
                EventUpdated::dispatch($event);
            }
            return redirect()->route('accom-reports.index')->with('status', 
                'AR background changed.');
        }
        $image = new Image($request->file('background_file'));
        Storage::put($imageFile, (string)$image->get());
        foreach ($gpoa->events as $event) {
            EventUpdated::dispatch($event);
        }
        return redirect()->route('accom-reports.index')->with('status', 
            'AR background changed.');
    }
}
