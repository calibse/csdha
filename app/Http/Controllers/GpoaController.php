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
            new Middleware('can:viewAny,' . GpoaActivity::class, 
                only: ['index']),
            new Middleware('can:create,' . Gpoa::class, 
                only: ['create', 'store']),
            new Middleware('can:update,' . Gpoa::class, 
                only: ['edit', 'update']),
            new Middleware('can:close,' . Gpoa::class, 
                only: ['confirmClose', 'close']),
        ];
    }

    public function index()
    {
        $gpoa = $this->gpoa; 
        if (!$gpoa) {
            return view('gpoa.index', [
                'gpoa' => $gpoa,
                'activities' => null
            ]);
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
        $period = AcademicPeriod::where('start_date', $request->start_date)
            ->where('end_date', $request->end_date)->first();
        if (!$period) {
            $period = new AcademicPeriod();
            $period->start_date = $request->start_date;
            $period->end_date = $request->end_date;
        }
        $period->term()->associate($term);
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

    public function showGenPdf(Request $request)
    {
        $gpoa = $this->gpoa; 
        return view('gpoa.show-gpoa-report', [
            'gpoa' => $gpoa,
            'fileRoute' => route('gpoa.genPdf', ['gpoa' => $gpoa->public_id ])
        ]);
    }

    public function genPdf(Request $request)
    {
        $gpoa = $this->gpoa; 
        return $gpoa->report();
    } 

    public function confirmClose(Request $request)
    {
        $gpoa = $this->gpoa; 
        return view('gpoa.close', ['gpoa' => $gpoa]);
    }

    public function close(Request $request)
    {
        $gpoa = $this->gpoa; 
        $gpoa->active = null;
        $gpoa->save();
        return redirect()->route('gpoa.index');
    }
    
    public function destroy(string $id)
    {

    }
}
