<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\PagedView;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\Image;
use App\Models\User;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\EventStudent;
use App\Models\StudentYear;
use App\Models\Course;
use Intervention\Image\Laravel\Facades\Image as IImage;
use App\Http\Requests\SaveEventDateRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\SaveAttendeeRequest;
use App\Services\Format;

class EventController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:viewAny,' . Event::class, only: ['index']),
            new Middleware('can:view,event', only: [
                'show',
                'showCoverPhoto',
                'showLetterOfIntentFile',
                'showLetterOfIntent',
                'showAttendance',
            ]),
            new Middleware('can:create,' . Event::class,
                only: ['create', 'store']),
            new Middleware('can:update,event', only: [
                'edit',
                'update',
                'dateIndex',
                'createDate',
                'storeDate',
                'editDate',
                'updateDate',
                'confirmDestroyDate',
                'destroyDate',
                'createAttendee',
                'storeAttendee',
            ]),
            new Middleware('can:delete,event', only: ['destroy']),
            new Middleware('can:update,date', only: [
                'editDate',
                'updateDate'
            ]),
            new Middleware('can:addAttendee,event', only: [
                'createAttendee',
                'storeAttendee'
            ]),
            new Middleware('can:recordAttendance,event', only: [
                'showAttendance',
            ]),
        ];
    }

    public function index(Request $request)
    {
		$events = Event::orderBy("updated_at", "desc")->paginate("7");
        return view('events.index', ["events" => $events]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        /*
        $event = new Event();
        $event->title = $request->title;
        if ($request->cover_photo) {
            $imageFile = 'event/cover_photo/' . Str::random(16) . '.jpg';
            $image = new Image($request->file('cover_photo')->get());
            Storage::put($imageFile, (string) $image->scaleDown(800));
            $event->cover_photo_filepath = $imageFile;
        }

        $event->venue = $request->venue;
        $event->type_of_activity = $request->type;
        $event->participants = $request->participants;
        $event->objective = $request->objective;
        $event->description = $request->description;
        $event->narrative = $request->narrative;

        $event->letter_of_intent = $request->letter
            ?->storeAs('events/letter_of_intents',
                      'letter_of_intent_' . Str::random(16) . '.pdf');
        $event->user_id = Auth::id();
        $event->save();
        $event->editors()->sync($request->editors);
        $event->save();
        return redirect()->route("events.index");
        */
    }

    public function show(Event $event)
    {
        return view('events.show', [
            'event' => $event,
            'activity' => $event->gpoaActivity,
            'editRoute' => route('events.edit', ['event' => $event->public_id]),
            'regisRoute' => route('events.registrations.consent.edit', [
                'event' => $event->public_id
            ]),
            'attendanceRoute' => route('events.attendance.show', [
                'event' => $event->public_id
            ]),
            'evalRoute' => route('events.evaluations.consent.edit', [
                'event' => $event->public_id
            ]),
            'eventHeads' => $event->gpoaActivity->eventHeadsOnly()->get(),
            'coheads' => $event->gpoaActivity->coheads()->get(),
            'backRoute' => route('events.index'),
            'genArRoute' => route('accom-reports.show', [
                'event' => $event->public_id,
                'from' => 'events'
            ]),
        ]);
    }

    public function edit(Request $request, Event $event)
    {
        $participantGroups = ['-1'];
        $selectedParticipants = [];
        $participants = StudentYear::all();
        if (session('errors')?->any() && old('participant_year_levels')
                && count(array_intersect(old('participant_year_levels'),
                    $participantGroups)) === 0) {
            $options = Format::getOpt(old('participant_year_levels'),
                $participants);
            $selectedParticipants = $options['selected'];
            $participants = $options['unselected'];
        } elseif ($event->participant_type === 'students') {
            $options = Format::getOpt($event->participants,
                $participants);
            $selectedParticipants = $options['selected'];
            $participants = $options['unselected'];
        }
        $backRoute = $request->from === 'accom-reports'
            ? route('accom-reports.show', [
                'event' => $event->public_id,
                'from' => $request->accom_reports_from,
            ])
            : route('events.show', [
                'event' => $event->public_id
            ]);
        return view("events.edit", [
            'officersOnly' => $event->participant_type === 'officers',
            'participants' => $participants,
            'selectedParticipants' => $selectedParticipants,
            'event' => $event,
            'backRoute' => $backRoute,
            'formAction' => route('events.update', [
                'event' => $event->public_id
            ]),
            'dateRoute' => route('events.dates.index', [
                'event' => $event->public_id
            ]),
            'evalRoute' => route('events.eval-form.edit-questions', [
                'event' => $event->public_id
            ]),
            'regisRoute' => route('events.regis-form.edit', [
                'event' => $event->public_id
            ]),
            'attachmentRoute' => route('events.attachments.index', [
                'event' => $event->public_id
            ]),
            'responsesRoute' => route('events.eval-form.edit-responses', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->venue = $request->venue;
        $event->description = $request->description;
        $event->narrative = $request->narrative;
        $event->tag = $request->tag;
        if ($request->record_attendance && !in_array('0',
                $request->record_attendance)) {
            if (in_array('-1', $request->record_attendance)) {
                $event->participant_type = 'officers';
                $event->participants()->sync([]);
                $event->automatic_attendance = false;
                $event->accept_evaluation = false;
            } else {
                $event->participant_type = 'students';
                $event->participants()->sync($request->record_attendance);
                $event->automatic_attendance = $request
                    ->boolean('automatic_attendance');
                $event->accept_evaluation = $request
                    ->boolean('accept_evaluation');
            }
        } else {
            $event->participant_type = null;
            $event->automatic_attendance = false;
            $event->accept_evaluation = false;
        }
        $event->save();
        return redirect()->route('events.show', [
            'event' => $event->public_id
        ]);
    }

    public function showCoverPhoto(Event $event)
    {
        return $event->cover_photo_filepath ? response()->file(Storage::path(
            $event->cover_photo_filepath)) : null;
    }

    public function destroy(string $id)
    {
        //
    }

    public function showLetterOfIntentFile(Event $event)
    {
        return response()->file(Storage::path($event->letter_of_intent));
    }

    public function showLetterOfIntent(Event $event)
    {
        return view('events.letter_of_intent', ['event' => $event]);
    }

    public function streamAccomReport(Request $request, Event $event)
    {
        $events[] = $event->accomReportViewData();
        $format = 'pdf';
        return match ($format) {
            'html' => view('events.accom-report', [
                'events' => $events,
                'editors' => User::withPerm('accomplishment-reports.edit')
                    ->notOfPosition('adviser')->get(),
                'approved' => $event->accomReport?->status === 'approved',
                'president' => User::ofPosition('president')->first()
            ]),
            'pdf' => WeasyPrint::prepareSource(new PagedView(
                'events.accom-report', [
                    'events' => $events,
                    'editors' => User::withPerm('accomplishment-reports.edit')
                        ->notOfPosition('adviser')->get(),
                    'approved' => $event->accomReport?->status === 'approved',
                    'president' => User::ofPosition('president')->first()
            ]))->stream('accom_report.pdf')
        };
    }

    public function showAttendance(Event $event)
    {
        $eventDates = $event->dates()->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')->get();
        return match ($event->participant_type) {
            'students' => view('events.show-attendance', [
                'event' => $event,
                'eventDates' => $eventDates,
                'backRoute' => route('events.show', [
                    'event' => $event->public_id
                ]),
                'addRoute' => route('events.attendance.create', [
                    'event' => $event->public_id
                ])
            ]),
            'officers' => view('events.show-attendance-officers', [
                'event' => $event,
                'eventDates' => $eventDates,
                'backRoute' => route('events.show', [
                    'event' => $event->public_id
                ]),
                'addRoute' => route('events.attendance.create', [
                    'event' => $event->public_id
                ])
            ])
        };
    }

    public function createAttendee(Event $event)
    {
        return match($event->participant_type) {
            'students' => view('events.add-attendee', [
                'dates' => $event->dates,
                'backRoute' => route('events.attendance.show', [
                    'event' => $event->public_id
                ]),
                'submitRoute' => route('events.attendance.store', [
                    'event' => $event->public_id
                ]),
                'programs' => Course::all(),
                'yearLevels' => $event->participants
            ]),
            'officers' => view('events.add-attendee-officer', [
                'dates' => $event->dates,
                'backRoute' => route('events.attendance.show', [
                    'event' => $event->public_id
                ]),
                'submitRoute' => route('events.attendance.store', [
                    'event' => $event->public_id
                ]),
                'officers' => User::has('position')->notOfPosition('adviser')
                    ->orderBy('first_name', 'asc')->get()
            ])
        };
    }

    public function storeAttendee(SaveAttendeeRequest $request, Event $event)
    {
        switch ($event->participant_type) {
        case 'students':
            $student = EventStudent::attended($event)
                ->where('student_id', $request->student_id)->first();
            $studentExists = true;
            if (!$student) {
                $studentExists = false;
                $student = new EventStudent();
            }
            $student->student_id = $request->student_id;
            $student->first_name = $request->first_name;
            $student->middle_name = $request->middle_name;
            $student->last_name = $request->last_name;
            $student->suffix_name = $request->suffix_name;
            $student->course()->associate(Course::find($request->program));
            $student->year = $request->year_level;
            $student->email = $request->email;
            $student->section = $request->section;
            $student->save();
            if (!$studentExists) {
                $date = EventDate::findByPublic($request->date);
                $date->attendees()->attach($student);
                $date->save();
            }
            break;
        case 'officers':
            $date = EventDate::findByPublic($request->date);
            $date->officerAttendees()->sync(User::whereIn('public_id',
                $request->officers ?? [])->get());
            $date->save();
        }
        return redirect()->route('events.attendance.show', [
            'event' => $event->public_id
        ])->with('status', 'Attendee added.');
    }

    public function dateIndex(Event $event)
    {
        return view('events.edit-dates',[
            'event' => $event
        ]);
    }

    public function createDate(Event $event) {
        return view('events.create-date', [
            'update' => false,
            'event' => $event,
            'date' => null
        ]);
    }

    public function editDate(Event $event, EventDate $date)
    {
        $date = $event->dates()->find($date->id);
        return view('events.create-date', [
            'update' => true,
            'event' => $event,
            'date' => $date
        ]);
    }

    private static function storeOrUpdateDate(Request $request, Event $event,
            EventDate $date = null)
    {
        if (!$date){
            $date = new EventDate();
            $date->event()->associate($event);
        } else {
            $date = $event->dates()->find($date->id);
        }
        $date->date = $request->date;
        $date->start_time = Format::toUtc($request->start_time);
        $date->end_time = Format::toUtc($request->end_time);
        $date->save();
    }

    public function storeDate(SaveEventDateRequest $request, Event $event)
    {
        self::storeOrUpdateDate($request, $event);
        return redirect()->route('events.dates.index',
            ['event' => $event->public_id])->with('status', 'Date saved.');
    }

    public function updateDate(SaveEventDateRequest $request, Event $event,
            EventDate $date)
    {
        self::storeOrUpdateDate($request, $event, $date);
        return redirect()->route('events.dates.index', ['event' => $event->public_id])
            ->with('status', 'Date updated.');
    }

    public function destroyDate(Event $event, EventDate $date)
    {
        $date->attendees()->detach();
        $date->delete();
        return redirect()->route('events.dates.index', ['event' => $event->public_id])
            ->with('status', 'Date deleted.');
    }

    public function confirmDestroyDate(Event $event, EventDate $date)
    {
        return view('events.delete-date', [
            'event' => $event,
            'date' => $date
        ]);
    }

}
