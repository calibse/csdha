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

class GpoaController extends Controller implements HasMiddleware
{
    private $gpoa;

    public function __construct()
    {
        $this->gpoa = Gpoa::active()->first();
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
        $gpoa = $this->gpoa;
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
        $gpoa->adviser()->associate(auth()->user());
        $gpoa->active = true;
        $gpoa->save();
    }

    public function show(Gpoa $gpoa)
    {

    }

    public function edit()
    {
        $gpoa = $this->gpoa;
        return view('gpoa.create', [
            'update' => true,
            'terms' => AcademicTerm::all(),
            'gpoa' => $gpoa
        ]);
    }

    public function update(SaveGpoaRequest $request)
    {
        $gpoa = $this->gpoa;
        self::storeOrUpdate($request, $gpoa);
        return redirect()->route('gpoa.index');
    }

    public function genPdf(Request $request)
    {
        $gpoa = $this->gpoa;
        $fileRoute = null;
        if ($gpoa->activities()->where('status', 'approved')->exists()) {
            $fileRoute = route('gpoa.genPdf');
        }
        return view('gpoa.show-gpoa-report', [
            'gpoa' => $gpoa,
            'fileRoute' => $fileRoute
        ]);
    }

    public function streamPdf(Request $request)
    {
        $gpoa = $this->gpoa;
        return WeasyPrint::prepareSource(new PagedView('gpoa.report',
            $gpoa->reportViewData()))->inline('gpoa_report.pdf');
    }

    public function confirmClose(Request $request)
    {
        $gpoa = $this->gpoa;
        return view('gpoa.close', ['gpoa' => $gpoa]);
    }

    public function close(Request $request)
    {
        $gpoa = $this->gpoa;
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
        $gpoa->active = null;
        $gpoa->closed_at = now();
        $gpoa->report_filepath = $reportFile;
        $gpoa->accom_report_filepath = $accomReportFile;
        $gpoa->save();
        return redirect()->route('gpoa.index');
    }

    public function destroy(string $id)
    {

    }
}
