<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Gpoa;
use App\Models\GpoaActivity;
use App\Models\GpoaActivityType;
use App\Models\GpoaActivityMode;
use App\Models\GpoaActivityFundSource;
use App\Models\GpoaActivityPartnershipType;
use App\Models\PositionCategory;
use App\Models\StudentYear;
use App\Models\User;
use App\Http\Requests\SaveGpoaActivityRequest;
use App\Http\Requests\SaveGpoaActivityCommentsRequest;
use App\Services\Format;
use App\Events\GpoaActivityStatusChanged;

class GpoaActivityController extends Controller implements HasMiddleware
{
    private $gpoa;

    public function __construct()
    {
        $this->gpoa = Gpoa::active()->first();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:create,' . GpoaActivity::class,
                only: ['create', 'store']),
            new Middleware('can:view,activity', only: ['show']),
            new Middleware('can:update,activity',
                only: ['edit', 'update', 'destroy', 'confirmDestroy']),
            new Middleware('can:submit,activity',
                only: ['prepareForSubmit', 'submit']),
            new Middleware('can:return,activity',
                only: ['prepareForReturn', 'return']),
            new Middleware('can:reject,activity',
                only: ['prepareForReject', 'reject']),
            new Middleware('can:approve,activity',
                only: ['prepareForApprove', 'approve'])
        ];
    }

    public function index()
    {
        //
    }

    public function create()
    {
        $gpoa = $this->gpoa;
        $selectedParticipants = [];
        $participants = StudentYear::all();
        $participantGroups = ['0'];
        if (session('errors')?->any() && old('participant_year_levels')
                && count(array_intersect(old('participant_year_levels'),
                    $participantGroups)) === 0) {

            $options = Format::getOpt(old('participant_year_levels'),
                $participants);
            $selectedParticipants = $options['selected'];
            $participants = $options['unselected'];
        }
        $selectedEventHeads = [];
        $eventHeads = User::has('position')->notAuthUser()->
                notOfPosition(['adviser'])->get();
        $eventHeadGroups = ['0'];
        if (session('errors')?->any() && old('event_heads')
                && count(array_intersect(old('event_heads'),
                    $eventHeadGroups)) === 0) {

            $options = Format::getOpt(old('event_heads'), $eventHeads);
            $selectedEventHeads = $options['selected'];
            $eventHeads = $options['unselected'];
        }
        $selectedCoheads = [];
        $coheads = User::has('position')->notAuthUser()->
                notOfPosition(['adviser'])->get();
        if (session('errors')?->any() && old('coheads')) {
            $options = Format::getOpt(old('coheads'), $coheads);
            $selectedCoheads = $options['selected'];
            $coheads = $options['unselected'];
        }
        return view('gpoa-activities.create', [
            'participants' => $participants,
            'selectedParticipants' => $selectedParticipants,
            'eventHeads' => $eventHeads,
            'selectedEventHeads' => $selectedEventHeads,
            'coheads' => $coheads,
            'selectedCoheads' => $selectedCoheads,
            'yearLevels' => StudentYear::all(),
            'activityTypes' => GpoaActivityType::all(),
            'modes' => GpoaActivityMode::all(),
            'partnershipTypes' => GpoaActivityPartnershipType::all(),
            'fundSources' => GpoaActivityFundSource::all(),
            'positionCateg' => PositionCategory::all(),
            'officers' => User::has('position')->notAuthUser()->
                notOfPosition(['adviser'])->get(),
            'gpoa' => $gpoa,
            'update' => false,
            'activity' => null,
            'backRoute' => route('gpoa.index'),
            'formAction' => route('gpoa.activities.store'),
        ]);
    }

    public function store(SaveGpoaActivityRequest $request)
    {
        $gpoa = $this->gpoa;
        $activity = self::storeOrUpdate($request, $gpoa);
        return redirect()->route('gpoa.activities.show', [
            'activity' => $activity->public_id
        ]);
    }

    public function show(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $actions = null;
        switch (auth()->user()->position_name) {
        case 'president':
            $actions = [
                'edit' => true,
                'submit' => true,
                'return' => true,
                'reject' => true,
                'approve' => false,
                'delete' => true
            ];
            break;
        case 'adviser':
            $actions = [
                'edit' => false,
                'submit' => false,
                'return' => true,
                'reject' => true,
                'approve' => true,
                'delete' => false
            ];
            break;
        default:
            $actions = [
                'edit' => true,
                'submit' => true,
                'return' => false,
                'reject' => false,
                'approve' => false,
                'delete' => true
            ];
        }
        $status = $activity->status;
        $step = $activity->current_step;
        $date = match ("${step}_${status}") {
            'officers_draft' => null,
            'president_draft' => null,
            'adviser_pending' => $activity->president_submitted_at,
            'president_pending' => $activity->officers_submitted_at,
            'officers_returned' => $activity->president_returned_at,
            'president_returned' => $activity->adviser_returned_at,
            'adviser_rejected' => $activity->rejected_at,
            'president_rejected' => $activity->rejected_at,
            'adviser_approved' => $activity->adviser_approved_at,
        };
        $date = $date?->timezone(config('timezone'))
            ->format(config('app.date_format'));

        return view('gpoa-activities.show', [
            'date' => $date,
            'gpoa' => $gpoa,
            'activity' => $activity,
            'eventHeads' => $activity->eventHeads()->wherePivot('role',
                'event head')->get(),
            'coheads' => $activity->eventHeads()->wherePivot('role',
                'co-head')->get(),
            'actions' => $actions
        ]);
    }

    public function edit(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $activity = $gpoa->activities()->find($activity->id);
        $participantGroups = ['0'];
        $selectedParticipants = [];
        $participants = StudentYear::all();
        $allAreParticipants = $activity->all_are_participants;
        if (session('errors')?->any() && old('participant_year_levels')
                && count(array_intersect(old('participant_year_levels'),
                    $participantGroups)) === 0) {
            $options = Format::getOpt(old('participant_year_levels'),
                $participants);
            $selectedParticipants = $options['selected'];
            $participants = $options['unselected'];
        } elseif (!$allAreParticipants) {
            $options = Format::getOpt($activity->participantTypes,
                $participants);
            $selectedParticipants = $options['selected'];
            $participants = $options['unselected'];
        }
        $allAreEventHeads = $activity->all_are_event_heads;
        $selectedEventHeads = [];
        $eventHeads = User::has('position')->notAuthUser()->
                notOfPosition(['adviser'])->get();
        $eventHeadGroups = ['0'];
        if (session('errors')?->any() && old('event_heads')
                && count(array_intersect(old('event_heads'),
                    $eventHeadGroups)) === 0) {

            $options = Format::getOpt(old('event_heads'), $eventHeads);
            $selectedEventHeads = $options['selected'];
            $eventHeads = $options['unselected'];
        } elseif (!$allAreEventHeads) {
            $options = Format::getOpt($activity->eventHeadsOnly()
                ->notAuthUser()->get(), $eventHeads);
            $selectedEventHeads = $options['selected'];
            $eventHeads = $options['unselected'];
        }
        $selectedCoheads = [];
        $coheads = User::has('position')->notAuthUser()->
                notOfPosition(['adviser'])->get();
        if (session('errors')?->any() && old('coheads')) {
            $options = Format::getOpt(old('coheads'), $coheads);
            $selectedCoheads = $options['selected'];
            $coheads = $options['unselected'];
        } else {
            $options = Format::getOpt($activity->coheads()->notAuthUser()
                ->get(), $coheads);
            $selectedCoheads = $options['selected'];
            $coheads = $options['unselected'];
        }
        return view('gpoa-activities.create', [
            'participants' => $participants,
            'selectedParticipants' => $selectedParticipants,
            'eventHeads' => $eventHeads,
            'selectedEventHeads' => $selectedEventHeads,
            'coheads' => $coheads,
            'selectedCoheads' => $selectedCoheads,
            'yearLevels' => StudentYear::all(),
            'activityTypes' => GpoaActivityType::all(),
            'modes' => GpoaActivityMode::all(),
            'partnershipTypes' => GpoaActivityPartnershipType::all(),
            'fundSources' => GpoaActivityFundSource::all(),
            'positionCateg' => PositionCategory::all(),
            'officers' => User::has('position')->notAuthUser()->
                notOfPosition(['adviser'])->get(),
            'gpoa' => $gpoa,
            'update' => true,
            'activity' => $activity,
            'authUserIsEventHead' => $activity->eventHeadsOnly()
                ->whereKey(auth()->user()->id)->exists(),
            'authUserIsCohead' => $activity->coheads()
                ->whereKey(auth()->user()->id)->exists(),
            'allAreParticipants' => $allAreParticipants,
            'allAreEventHeads' => $activity->all_are_event_heads,
            'backRoute' => route('gpoa.activities.show', [
                'activity' => $activity->public_id
            ]),
            'formAction' => route('gpoa.activities.update', [
                'activity' => $activity->public_id
            ]),
        ]);
    }

    public function update(SaveGpoaActivityRequest $request,
            GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        self::storeOrUpdate($request, $gpoa, $activity);
        return redirect()->route('gpoa.activities.show', [
            'activity' => $activity->public_id
        ]);
    }

    public function confirmDestroy(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $activity = $gpoa->activities()->find($activity->id);
        return view('gpoa-activities.delete', [
            'gpoa' => $gpoa,
            'activity' => $activity
        ]);
    }

    public function destroy(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $activity = $gpoa->activities()->find($activity->id);
        $activity->eventHeads()->detach();
        $activity->participantTypes()->detach();
        $activity->delete();
        return redirect()->route('gpoa.index')
            ->with('status', 'Activity deleted.');
    }

    public function prepareForSubmit(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        return view('gpoa-activities.prepare', [
            'action' => 'Submit',
            'gpoa' => $gpoa,
            'activity' => $activity,
            'formAction' => route('gpoa.activities.submit', [
                'activity' => $activity->public_id
            ]),
            'backRoute' => route('gpoa.activities.show', [
                'activity' => $activity->public_id
            ]),
        ]);
    }

    public function prepareForReturn(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        return view('gpoa-activities.prepare', [
            'action' => 'Return',
            'route' => 'gpoa.activities.return',
            'gpoa' => $gpoa,
            'activity' => $activity,
            'formAction' => route('gpoa.activities.return', [
                'activity' => $activity->public_id
            ]),
            'backRoute' => route('gpoa.activities.show', [
                'activity' => $activity->public_id
            ]),
        ]);
    }

    public function prepareForReject(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        return view('gpoa-activities.prepare', [
            'action' => 'Reject',
            'route' => 'gpoa.activities.reject',
            'gpoa' => $gpoa,
            'activity' => $activity,
            'formAction' => route('gpoa.activities.reject', [
                'activity' => $activity->public_id
            ]),
            'backRoute' => route('gpoa.activities.show', [
                'activity' => $activity->public_id
            ]),
        ]);
    }

    public function prepareForApprove(GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        return view('gpoa-activities.prepare', [
            'action' => 'Approve',
            'route' => 'gpoa.activities.approve',
            'gpoa' => $gpoa,
            'activity' => $activity,
            'formAction' => route('gpoa.activities.approve', [
                'activity' => $activity->public_id
            ]),
            'backRoute' => route('gpoa.activities.show', [
                'activity' => $activity->public_id
            ]),
        ]);
    }

    public function submit(SaveGpoaActivityCommentsRequest $request,
            GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $activity->comments = $request->comments;
        switch(auth()->user()->position_name) {
        case 'president':
            $activity->status = 'pending';
            $activity->current_step = 'adviser';
            $activity->president_submitted_at = now();
            $activity->save();
            break;
        default:
            $activity->status = 'pending';
            $activity->current_step = 'president';
            $activity->officers_submitted_at = now();
            $activity->save();
        }
        GpoaActivityStatusChanged::dispatch($activity);
        return redirect()->route('gpoa.activities.show', [
            'activity' => $activity->public_id
        ]);
    }

    public function return(SaveGpoaActivityCommentsRequest $request,
            GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $activity->comments = $request->comments;
        $status = '';
        switch(auth()->user()->position_name) {
        case 'president':
            $activity->status = 'returned';
            $activity->current_step = 'officers';
            $activity->president_returned_at = now();
            $activity->save();
            $status = 'Activity returned to the officers.';
            break;
        case 'adviser':
            $activity->status = 'returned';
            $activity->current_step = 'president';
            $activity->adviser_returned_at = now();
            $activity->save();
            $status = 'Activity returned to the President.';
            break;
        }
        GpoaActivityStatusChanged::dispatch($activity);
        return redirect()->route('gpoa.index')
            ->with('status', $status);
    }

    public function reject(SaveGpoaActivityCommentsRequest $request,
            GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $activity->comments = $request->comments;
        $activity->status = 'rejected';
        $activity->rejected_at = now();
        $activity->save();
        GpoaActivityStatusChanged::dispatch($activity);
        return redirect()->route('gpoa.index')->with('status',
            'Activity rejected.');
    }

    public function approve(SaveGpoaActivityCommentsRequest $request,
            GpoaActivity $activity)
    {
        $gpoa = $this->gpoa;
        $activity->createEvent();
        $activity->comments = $request->comments;
        $activity->status = 'approved';
        $activity->adviser_approved_at = now();
        $activity->save();
        GpoaActivityStatusChanged::dispatch($activity);
        return redirect()->route('gpoa.index')->with('status',
            'Activity approved.');
    }

    private static function storeOrUpdate(Request $request, Gpoa $gpoa,
            GpoaActivity $activity = null)
    {
        $update = true;
        if (!$activity) {
            $activity = new GpoaActivity();
            $update = false;
        }
        $type = GpoaActivityType::findByName($request->type_of_activity);
        if (!$type && $request->type_of_activity) {
            $type = new GpoaActivityType();
            $type->name = $request->type_of_activity;
            $type->save();
            $activity->type()->associate($type);
        } elseif ($type) {
            $activity->type()->associate($type);
        }
        $fundSource = GpoaActivityFundSource::findByName($request
            ->fund_source);
        if (!$fundSource && $request->fund_source) {
            $fundSource = new GpoaActivityFundSource();
            $fundSource->name = $request->fund_source;
            $fundSource->save();
            $activity->fundSource()->associate($fundSource);
        } elseif ($fundSource) {
            $activity->fundSource()->associate($fundSource);
        }
        $mode = GpoaActivityMode::findByName($request->mode);
        if (!$mode && $request->mode) {
            $mode = new GpoaActivityMode();
            $mode->name = $request->mode;
            $mode->save();
            $activity->mode()->associate($mode);
        } elseif ($mode) {
            $activity->mode()->associate($mode);
        }
        $partnershipType = GpoaActivityPartnershipType::findByName($request
            ->partnership);
        if (!$partnershipType && $request->partnership) {
            $partnershipType = new GpoaActivityPartnershipType();
            $partnershipType->name = $request->partnership;
            $partnershipType->save();
            $activity->partnershipType()->associate($partnershipType);
        } elseif ($partnershipType) {
            $activity->partnershipType()->associate($partnershipType);
        }
        $activity->name = $request->name;
        $activity->start_date = $request->start_date;
        $activity->end_date = $request->end_date;
        $activity->objectives = $request->objectives;
        $activity->participants = $request->participants_description;
        $activity->proposed_budget = $request->proposed_budget;
        $activity->gpoa()->associate($gpoa);
        $activity->status = 'draft';
        if (auth()->user()->position_name === 'president') {
            $activity->current_step = 'president';
        } else {
            $activity->current_step = 'officers';
        }
        $activity->number_of_participants = 0;
        $activity->save();
        if (($update && auth()->user()->can('updateEventHeads', $activity)) ||
                !$update) {
            $allAreEventHeads = false;
            if ($request->event_heads && in_array('0',
                    $request->event_heads)) {
                $activity->eventHeads()->syncWithPivotValues(
                    User::has('position')->notOfPosition(['adviser'])->get(),
                    ['role' => 'event head']);
                $allAreEventHeads = true;
            } elseif ($request->event_heads) {
                $eventHeads = User::whereIn('public_id', $request->event_heads)
                    ->pluck('id')->toArray();
                $activity->eventHeads()->syncWithPivotValues($eventHeads,
                    ['role' => 'event head']);
                $activity->eventheads()->attach(auth()->user(),
                    ['role' => 'event head']);
            } else {
                $activity->eventheads()->syncWithPivotValues([auth()->user()],
                    ['role' => 'event head']);
            }
            if (!$allAreEventHeads && $request->coheads) {
                $coheads = User::whereIn('public_id', array_diff($request
                    ->coheads, $request->event_heads ?? []))->pluck('id')
                    ->toArray();
                $activity->eventHeads()->syncWithPivotValues($coheads,
                    ['role' => 'co-head'], false);
            }
        }
        $activity->save();
        return $activity;
    }
}
