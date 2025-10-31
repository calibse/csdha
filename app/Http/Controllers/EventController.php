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
use App\Models\EventEvaluation;
use Intervention\Image\Laravel\Facades\Image as IImage;
use App\Http\Requests\SaveEventDateRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\SaveAttendeeRequest;
use App\Http\Requests\UpdateCommentsRequest;
use App\Http\Requests\UpdateEventNarrativeRequest;
use App\Services\Format;
use App\Mail\EventEvaluation as EventEvaluationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DateTimeZone;
use App\Events\EventUpdated;
use App\Events\EventDatesChanged;
use App\Models\Gpoa;
use Illuminate\Support\Carbon;

class EventController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.index:viewAny,' . Event::class, only: ['index']),
            new Middleware('auth.event:view,event', only: [
                'show',
                'showCoverPhoto',
                'showLetterOfIntentFile',
                'showLetterOfIntent',
                'showAttendance',
            ]),
            new Middleware('auth.index:create,' . Event::class,
                only: ['create', 'store']),
            new Middleware('auth.event:update,event', only: [
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
                'editComments',
                'updateComments'
            ]),
            new Middleware('auth.event:delete,event', only: ['destroy']),
            new Middleware('auth.event:update,date', only: [
                'editDate',
                'updateDate'
            ]),
            new Middleware('auth.event:addAttendee,event', only: [
                'createAttendee',
                'storeAttendee'
            ]),
            new Middleware('auth.event:recordAttendance,event', only: [
                'showAttendance',
            ]),
        ];
    }

    public function index(Request $request)
    {
	$events = Event::active()->orderBy("updated_at", "desc")->paginate("7");
        $gpoa = Gpoa::active()->exists();
        return view('events.index', [
            'gpoa' => $gpoa,
            'events' => $events
        ]);
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
        $selectedParticipants = $event->participants;
        $participants = StudentYear::all();
        if (session('errors')?->any()) { 
            $selectedCourses = Course::whereIn('id', 
                old('student_courses') ?? [])->get();
            $courses = Course::whereNotIn('id', 
                old('student_courses') ?? [])->get();
        } else {
            $selectedCourses = $event->courses;
            $courses = Course::whereDoesntHave('events', function ($query) 
                use ($event) {
                $query->where('events.id', $event->id);
            })->get();
        }
        $backRoute = $request->from === 'accom-reports'
            ? route('accom-reports.show', [
                'event' => $event->public_id,
                'from' => $request->accom_reports_from,
            ])
            : route('events.show', [
                'event' => $event->public_id
            ]);
        $timezones = DateTimeZone::listIdentifiers();
        return view("events.edit", [
            'timezones' => $timezones,
            'officersOnly' => $event->participant_type === 'officers',
            'participants' => $participants,
            'selectedParticipants' => $selectedParticipants,
            'courses' => $courses,
            'selectedCourses' => $selectedCourses,
            'event' => $event,
            'backRoute' => $backRoute,
            'formAction' => route('events.update', [
                'event' => $event->public_id
            ]),
            'narrativeRoute' => route('events.narrative.edit', [
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
            'commentsRoute' => route('events.evaluations.comments.edit', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->venue = $request->venue;
        $event->description = $request->description;
        $event->tag = $request->tag;
        $event->timezone = $request->timezone;
        $event->evaluation_delay_hours = $request->evaluation_delay_hours ?? 0;
        if ($request->record_attendance && !in_array('0',
                $request->record_attendance)) {
            if (in_array('-1', $request->record_attendance)) {
                $event->participant_type = 'officers';
                $event->participants()->sync([]);
                $event->courses()->sync([]);
                $event->automatic_attendance = false;
                $event->accept_evaluation = false;
            } else {
                $event->participant_type = 'students';
                $event->participants()->sync($request->record_attendance);
                $event->courses()->sync($request->student_courses);
                $event->automatic_attendance = $request
                    ->boolean('automatic_attendance');
                $event->accept_evaluation = $request
                    ->boolean('accept_evaluation');
            }
        } else {
            $event->participant_type = null;
            $event->participants()->sync([]);
            $event->courses()->sync([]);
            $event->automatic_attendance = false;
            $event->accept_evaluation = false;
        }
        $event->save();
        if ($event->accept_evaluation) {
            // self::sendEvaluationForm($event);
        }
        EventUpdated::dispatch($event);
        return redirect()->route('events.show', [
            'event' => $event->public_id
        ]);
    }

    public function editNarrative(Event $event)
    {
        return view('events.edit-narrative', [
            'formAction' => route('events.narrative.update', [
                 'event' => $event->public_id
             ]),
            'backRoute' => route('events.edit', [
                 'event' => $event->public_id
             ]),
             'narrative' => $event->narrative
        ]);
    }

    public function updateNarrative(UpdateEventNarrativeRequest $request, 
        Event $event)
    {
        $event->narrative = $request->narrative;
        $event->save();
        return back()->with('status', 'Event narrative updated.');
    }

    private static function sendEvaluationForm(Event $event): void
    {
        foreach ($event->dates as $date) {
            foreach ($date->attendees as $attendee) {
                $token = self::createToken();
                $url = route('events.evaluations.consent.edit', [
                    'event' => $event->public_id,
                    'token' => $token
                ]);
                Mail::to($attendee->email)->send(new EventEvaluationMail(
                    $attendee, $event->gpoaActivity->name, $url));
            }
        }
    }

    private static function createToken(): string
    {
        $token = Str::random(64);
        DB::table('event_evaluation_tokens')->insert([
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        return $token;
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
            'html' => view('events.accom-report', $event
                 ->accomReportViewData()),
            'pdf' => WeasyPrint::prepareSource(new PagedView(
                'events.accom-report', $event->accomReportViewData()))
                ->stream('accom_report.pdf')
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
            'dates' => $event->dates()->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc')->get(),
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
        $date->start_time = $request->start_time;
        $date->end_time = $request->end_time;
        $date->save();
    }

    public function storeDate(SaveEventDateRequest $request, Event $event)
    {
        self::storeOrUpdateDate($request, $event);
        EventDatesChanged::dispatch($event);
        EventUpdated::dispatch($event);
        return redirect()->route('events.dates.index', [
            'event' => $event->public_id
        ])->with('status', 'Date saved.');
    }

    public function updateDate(SaveEventDateRequest $request, Event $event,
            EventDate $date)
    {
        self::storeOrUpdateDate($request, $event, $date);
        return redirect()->route('events.dates.index', [
            'event' => $event->public_id
        ])->with('status', 'Date updated.');
    }

    public function destroyDate(Event $event, EventDate $date)
    {
        $date->attendees()->detach();
        $date->delete();
        EventDatesChanged::dispatch($event);
        return redirect()->route('events.dates.index', [
            'event' => $event->public_id
        ])->with('status', 'Date deleted.');
    }

    public function confirmDestroyDate(Event $event, EventDate $date)
    {
        return view('events.delete-date', [
            'event' => $event,
            'date' => $date
        ]);
    }

    public function editComments(Request $request, Event $event)
    {
        $type = $request->type;
        $selectedComments = collect();
        $unselectedComments = collect();
        $commentType = '';
        $typeRoutes = [
            'Topics Covered' => route('events.evaluations.comments.edit', [
                'event' => $event->public_id,
                'type' => 'topics covered'
            ]),
            'Suggestions for Improvement' => route('events.evaluations.'
                    . 'comments.edit', [
                'event' => $event->public_id,
                'type' => 'suggestions for improvement'
            ]),
            'Future Topics' => route('events.evaluations.comments.edit', [
                'event' => $event->public_id,
                'type' => 'future topics'
            ]),
            'Overall Experience' => route('events.evaluations.comments.edit', [
                'event' => $event->public_id,
                'type' => 'overall experience'
            ]),
            'Additional Comments' => route('events.evaluations.comments.edit', [
                'event' => $event->public_id,
                'type' => 'additional comments'
            ]),
        ];
        switch ($type) {
        case 'topics covered':
            $comments = $event->evaluations()->select('id',
                'topics_covered as comment',
                'feature_topics_covered as feature')
                ->whereNotNull('topics_covered')
                ->orderByRaw('length(comment) desc')
                ->get();
            $commentOpt = self::getCommentOpt($comments);
            $selectedComments = $commentOpt['selected'];
            $unselectedComments = $commentOpt['unselected'];
            $commentType = 'Topics Covered';
            break;
        case 'suggestions for improvement':
            $comments = $event->evaluations()->select('id',
                'suggestions_for_improvement as comment',
                'feature_suggestions_for_improvement as feature')
                ->whereNotNull('suggestions_for_improvement')
                ->orderByRaw('length(comment) desc')
                ->get();
            $commentOpt = self::getCommentOpt($comments);
            $selectedComments = $commentOpt['selected'];
            $unselectedComments = $commentOpt['unselected'];
            $commentType = 'Suggestions for Improvement';
            break;
        case 'future topics':
            $comments = $event->evaluations()->select('id',
                'future_topics as comment',
                'feature_future_topics as feature')
                ->whereNotNull('future_topics')
                ->orderByRaw('length(comment) desc')
                ->get();
            $commentOpt = self::getCommentOpt($comments);
            $selectedComments = $commentOpt['selected'];
            $unselectedComments = $commentOpt['unselected'];
            $commentType = 'Future Topics';
            break;
        case 'overall experience':
            $comments = $event->evaluations()->select('id',
                'overall_experience as comment',
                'feature_overall_experience as feature')
                ->whereNotNull('overall_experience')
                ->orderByRaw('length(comment) desc')
                ->get();
            $commentOpt = self::getCommentOpt($comments);
            $selectedComments = $commentOpt['selected'];
            $unselectedComments = $commentOpt['unselected'];
            $commentType = 'Overall Experience';
            break;
        case 'additional comments':
            $comments = $event->evaluations()->select('id',
                'additional_comments as comment',
                'feature_additional_comments as feature')
                ->whereNotNull('additional_comments')
                ->orderByRaw('length(comment) desc')
                ->get();
            $commentOpt = self::getCommentOpt($comments);
            $selectedComments = $commentOpt['selected'];
            $unselectedComments = $commentOpt['unselected'];
            $commentType = 'Additional Comments';
            break;
        }
        return view('events.edit-comments', [
            'selectedComments' => $selectedComments,
            'unselectedComments' => $unselectedComments,
            'typeRoutes' => $typeRoutes,
            'backRoute' => route('events.edit', [
                'event' => $event->public_id
            ]),
            'commentType' => $commentType,
            'formAction' => route('events.evaluations.comments.update', [
                'event' => $event->public_id,
                'type' => $type
            ]),
        ]);
    }

    public function updateComments(UpdateCommentsRequest $request, Event $event)
    {
        $comments = $request->comments;
        $type = $request->type;
        switch ($type) {
        case 'topics covered':
            $evals = $event->evaluations()->select('id',
                'topics_covered as comment',
                'feature_topics_covered as feature')
                ->whereNotNull('topics_covered')->get();
            foreach ($evals as $eval) {
                $eval->feature_topics_covered = $comments[$eval->id] ?? false;
                $eval->save();
            }
            break;
        case 'suggestions for improvement':
            $evals = $event->evaluations()->select('id',
                'suggestions_for_improvement as comment',
                'feature_suggestions_for_improvement as feature')
                ->whereNotNull('suggestions_for_improvement')
                ->get();
            foreach ($evals as $eval) {
                $eval->feature_suggestions_for_improvement =
                    $comments[$eval->id] ?? false;
                $eval->save();
            }
            break;
        case 'future topics':
            $evals = $event->evaluations()->select('id',
                'future_topics as comment',
                'feature_future_topics as feature')
                ->whereNotNull('future_topics')
                ->get();
            foreach ($evals as $eval) {
                $eval->feature_future_topics = $comments[$eval->id] ?? false;
                $eval->save();
            }
            break;
        case 'overall experience':
            $evals = $event->evaluations()->select('id',
                'overall_experience as comment',
                'feature_overall_experience as feature')
                ->whereNotNull('overall_experience')
                ->get();
            foreach ($evals as $eval) {
                $eval->feature_overall_experience = $comments[$eval->id]
                    ?? false;
                $eval->save();
            }
            break;
        case 'additional comments':
            $evals = $event->evaluations()->select('id',
                'additional_comments as comment',
                'feature_additional_comments as feature')
                ->whereNotNull('additional_comments')
                ->get();
            foreach ($evals as $eval) {
                $eval->feature_additional_comments = $comments[$eval->id]
                    ?? false;
                $eval->save();
            }
            break;
        }
        return redirect()->back()->with('status',
            'Evaluation comments updated.');
    }

    private static function getCommentOpt($comments)
    {
        $options = collect();
        $options['selected'] = collect();
        $options['unselected'] = collect();
        foreach ($comments as $comment) {
            if ($comment->feature) {
                $options['selected'][] = $comment;
            } else {
                $options['unselected'][] = $comment;
            }
        }
        return $options;
    }
}
