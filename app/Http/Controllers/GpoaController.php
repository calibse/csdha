<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\User;
use App\Models\Position;
use App\Models\Gpoa;
use App\Models\GpoaActivity;
use App\Models\AcademicTerm;
use App\Models\AcademicPeriod;
use Illuminate\Support\Carbon;
use App\Services\Format;
use App\Http\Requests\SaveGpoaRequest;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;
use Illuminate\Support\Facades\Storage;
use App\Events\GpoaStatusChanged;

class GpoaController extends Controller implements HasMiddleware
{
    private static $gpoa;

    public function __construct()
    {
        self::$gpoa = Gpoa::active()->first();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth.index:viewAny,' . GpoaActivity::class,
                only: ['index']),
            new Middleware('auth.index:create,' . Gpoa::class,
                only: ['create', 'store']),
            new Middleware('auth.index:update,' . Gpoa::class,
                only: ['edit', 'update']),
            new Middleware('auth.index:close,' . Gpoa::class,
                only: ['confirmClose', 'close']),
            new Middleware('auth.index:genPdf,' . Gpoa::class,
                only: ['genPdf', 'streamPdf']),
        ];
    }

    public function index()
    {
        $gpoa = self::$gpoa;
        if (!$gpoa) {
            return view('gpoa.index', ['gpoa' => $gpoa, 'activities' => null]);
        }
        switch(auth()->user()->position_name) {
        case 'president':
            $activities = $gpoa->activities()->forPresident();
            break;
        case 'adviser':
            $activities = $gpoa->activities()->forAdviser();
            break;
        default:
            $activities = $gpoa->activities();
        }
        return view('gpoa.index', [
            'gpoa' => $gpoa,
            'activities' => $activities->orderBy('updated_at', 'desc')
                ->paginate(7)
        ]);
    }

    public function oldIndex()
    {
        $gpoas = Gpoa::closed()->withApprovedActivity()
            ->orderBy('closed_at', 'desc')->paginate(7);
        return view('gpoa.index-old', [
            'gpoas' => $gpoas,
            'backRoute' => route('gpoa.index'),
        ]);
    }

    public function create()
    {
        return view('gpoa.create', [
            'update' => false,
            'terms' => AcademicTerm::all(),
            'gpoa' => null
        ]);
    }

    public function store(SaveGpoaRequest $request)
    {
        self::storeOrUpdate($request);
        GpoaStatusChanged::dispatch();
        return redirect()->route('gpoa.index');
    }

    private static function storeOrUpdate(Request $request, Gpoa $gpoa = null)
    {
        if (!$gpoa) $gpoa = new Gpoa();
        $term = AcademicTerm::find($request->academic_term);
        if ($gpoa->academicPeriod()->exists()) {
            $period = $gpoa->academicPeriod;
        } else {
            $period = new AcademicPeriod();
        }
        $period->start_date = $request->start_date;
        $period->end_date = $request->end_date;
        $period->term()->associate($term);
        $period->head_of_student_services = $request->head_of_student_services;
        $period->branch_director = $request->branch_director;
        $period->save();
        $gpoa->academicPeriod()->associate($period);
        $gpoa->creator()->associate(auth()->user());
        $gpoa->save();
    }

    public function show(Gpoa $gpoa)
    {
        return view('gpoa.show', [
            'reportRoute' => route('gpoas.report.show', [
                'gpoa' => $gpoa->public_id
            ]),
            'accomReportRoute' => route('gpoas.accom-report.show', [
                'gpoa' => $gpoa->public_id
            ]),
            'createdBy' => $gpoa->creator?->full_name,
            'closedBy' => $gpoa->closer?->full_name,
            'academicPeriod' => $gpoa->full_academic_period,
            'activityCount' => $gpoa->activities()->count(),
            'accomReportCount' => $gpoa->events()->approved()->count(),
            'backRoute' => route('gpoas.old-index'),
        ]);
    }

    public function showReport(Gpoa $gpoa)
    {
        return view('gpoa.show-gpoa-report', [
            'fileRoute' => route('gpoas.report-file.show', [
                'gpoa' => $gpoa->public_id
            ]),
            'backRoute' => route('gpoas.show', [
                'gpoa' => $gpoa->public_id
            ]),
        ]);
    }

    public function showReportFile(Gpoa $gpoa)
    {
        $file = $gpoa->report_filepath;
        if (!$file) abort(404);
        return response()->file(Storage::path($file));
    }

    public function showAccomReport(Gpoa $gpoa)
    {
        return view('gpoa.show-accom-report', [
            'fileRoute' => route('gpoas.accom-report-file.show', [
                'gpoa' => $gpoa->public_id
            ]),
            'backRoute' => route('gpoas.show', [
                'gpoa' => $gpoa->public_id
            ]),
        ]);
    }

    public function showAccomReportFile(Gpoa $gpoa)
    {
        $file = $gpoa->accom_report_filepath;
        if (!$file) abort(404);
        return response()->file(Storage::path($file));
    }

    public function edit()
    {
        $gpoa = self::$gpoa;
        return view('gpoa.create', [
            'update' => true,
            'terms' => AcademicTerm::all(),
            'gpoa' => $gpoa
        ]);
    }

    public function update(SaveGpoaRequest $request)
    {
        $gpoa = self::$gpoa;
        self::storeOrUpdate($request, $gpoa);
        return redirect()->route('gpoa.index');
    }

    public function genPdf(Request $request)
    {
        $gpoa = self::$gpoa;
        $fileRoute = null;
        if ($gpoa->activities()->where('status', 'approved')->exists()) {
            $fileRoute = route('gpoa.genPdf');
        }
        return view('gpoa.show-gpoa-report', [
            'gpoa' => $gpoa,
            'fileRoute' => $fileRoute,
            'backRoute' => route('gpoa.index'),
        ]);
    }

    public function streamPdf(Request $request)
    {
        $gpoa = self::$gpoa;
        return WeasyPrint::prepareSource(new PagedView('gpoa.report',
            $gpoa->reportViewData()))->inline('gpoa_report.pdf');
    }

    public function confirmClose(Request $request)
    {
        $gpoa = self::$gpoa;
        return view('gpoa.close', ['gpoa' => $gpoa]);
    }

    public function close(Request $request)
    {
        $gpoa = self::$gpoa;
        $status = 'GPOA closed.';
        if (!$gpoa->has_approved_activity) {
            self::destroyGpoa();
            GpoaStatusChanged::dispatch();
            return redirect()->route('gpoa.index')->with('status', $status);
        }
        $reportFile = "gpoas/gpoa_{$gpoa->id}/gpoa_report.pdf";
        $accomReportFile = "gpoas/gpoa_{$gpoa->id}/accom_report.pdf";
        WeasyPrint::prepareSource(new PagedView('gpoa.report',
            $gpoa->reportViewData()))->putFile($reportFile);
        $allEvents = $gpoa->events()->approved()->get();
        $events = [];
        foreach ($allEvents as $event) {
            $events[] = $event->accomReportViewData();
        }
        WeasyPrint::prepareSource(new PagedView('events.accom-report', [
            'events' => $events,
            'editors' => User::withPerm('accomplishment-reports.edit')
                ->notOfPosition('adviser')->get(),
            'approved' => true,
            'president' => User::ofPosition('president')->first()
        ]))->putFile($accomReportFile);
        $gpoa->report_filepath = $reportFile;
        $gpoa->accom_report_filepath = $accomReportFile;
        $gpoa->save();
        self::closeGpoa();
        GpoaStatusChanged::dispatch();
        return redirect()->route('gpoa.index')->with('status', $status);
    }

    private static function destroyGpoa(): void
    {
        $gpoa = self::$gpoa;
        $gpoa->activities()?->delete();
        $gpoa->delete();
    }

    private static function closeGpoa(): void
    {
        $gpoa = self::$gpoa;
        $gpoa->closer()->associate(auth()->user());
        $gpoa->closed_at = now();
        $gpoa->save();
    }

    public function destroy(string $id)
    {

    }
}
