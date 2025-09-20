<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventEvaluation;
use App\Models\EventStudent;
use App\Models\Course;
use App\Models\StudentYear;
use App\EventEvaluationToken;
use App\Services\Format;
use App\Http\Requests\StoreEventEvalIdentityRequest;
use App\Http\Requests\StoreEventEvalRequest;
use App\Http\Requests\StoreConsentRequest;
use Illuminate\Support\Str;

class EventEvaluationController extends Controller
{
    private static string $sessionDataName;

    public function __construct()
    {
        self::$sessionDataName = 'event_evaluation';
    }

    public function editConsentStep(Request $request, Event $event)
    {
        $inputs = session(self::$sessionDataName, []);
        return view('event-evaluations.consent', [
            'submitRoute' => route('events.evaluations.consent.store', [
                'event' => $event->public_id
            ]),
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event));
    }

    public function storeConsentStep(StoreConsentRequest $request,
            Event $event)
    {
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route('events.evaluations.identity.edit', [
            'event' => $event->public_id
        ]);
    }

    public function editIdentityStep(Request $request, Event $event)
    {
        $inputs = session(self::$sessionDataName, []);
        return view('event-evaluations.identity', [
            'programs' => Course::all(),
            'yearLevels' => StudentYear::all(),
            'previousStepRoute' => route('events.evaluations.consent.edit', [
                'event' => $event->public_id
            ]),
            'submitRoute' => route('events.evaluations.identity.store', [
                'event' => $event->public_id
            ]),
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event));
    }

    public function storeIdentityStep(StoreEventEvalIdentityRequest $request,
            Event $event)
    {
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route('events.evaluations.evaluation.edit', [
            'event' => $event->public_id
        ]);
    }

    public function editEvaluationStep(Request $request, Event $event)
    {
        $inputs = session(self::$sessionDataName, []);
        return view('event-evaluations.evaluation', [
            'previousStepRoute' => route('events.evaluations.identity.edit', [
                'event' => $event->public_id
            ]),
            'submitRoute' => route('events.evaluations.evaluation.store', [
                'event' => $event->public_id
            ]),
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event));
    }

    public function storeEvaluationStep(StoreEventEvalRequest $request,
            Event $event)
    {
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route('events.evaluations.acknowledgement.edit', [
            'event' => $event->public_id
        ]);
    }

    public function editAcknowledgementStep(Request $request, Event $event)
    {
        $inputs = session(self::$sessionDataName, []);
        return view('event-evaluations.acknowledgement', [
            'previousStepRoute' => route('events.evaluations.evaluation.edit',
                [
                'event' => $event->public_id
            ]),
            'submitRoute' => route('events.evaluations.acknowledgement.store',
                [
                'event' => $event->public_id
            ]),
            'lastStep' => true,
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event));
    }

    public function storeAcknowledgementStep(Request $request, Event $event)
    {
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route('events.evaluations.end.show', [
            'event' => $event->public_id
        ]);
    }

    public function showEndStep(Request $request, Event $event)
    {
        self::store($event);
        session()->forget(self::$sessionDataName);
        return view('event-evaluations.end', [
            'end' => true
        ] + self::multiFormData($event));
    }

    public function sendEvaluationForms(Event $event)
    {
        foreach ($event->dates() as $date) {
            foreach ($date->attendees as $attendee) {
                $token = self::createToken();
                $url = route('events.evaluations.consent.edit', [
                    'token' => $token
                ]);
                //Mail::to($attendee->email)->send(new
            }
        }
    }

    private static function createToken(): string
    {
        $rawToken = Str::random(64);
        $token = new EventEvaluationToken;
        $token->token = Hash::make($rawToken);
        $token->save();
        return $rawToken;
    }

    private static function store(Event $event): void
    {
        $inputs = session(self::$sessionDataName, []);
        $studentInput = $inputs['events.evaluations.identity'];
        $studentId = $studentInput['student_id'];
        $student = EventStudent::whereHas('eventDate.event',
            function ($query) use ($event) {
                $query->whereKey($event->id);
            })->where('student_id', $studentId)->first();
        if (!$student) {
            return;
        }
        $attendee = $student->eventAttended;
        $evalInput = $inputs['events.evaluations.evaluation'];
        $eval = new EventEvaluation;
        $eval->event()->associate($event);
        $eval->attendee()->associate($attendee);
        $eval->overall_satisfaction = $evalInput['overall_satisfaction'];
        $eval->content_relevance = $evalInput['content_relevance'];
        $eval->speaker_effectiveness = $evalInput['speaker_effectiveness'];
        $eval->engagement_level = $evalInput['engagement_level'];
        $eval->duration = $evalInput['duration'];
        $eval->topics_covered = $evalInput['topics_covered'];
        $eval->suggestions_for_improvement = $evalInput[
            'suggestions_for_improvement'];
        $eval->future_topics = $evalInput['future_topics'];
        $eval->overall_experience = $evalInput['overall_experience'];
        $eval->additional_comments = $evalInput['additional_comments'];
        $eval->selected = false;
        $eval->save();
    }

    private static function multiFormData(Event $event): array
    {
        return [
            'formTitle' => 'Evaluation',
            'eventName' => $event->gpoaActivity->name,
            'event' => $event,
        ];
    }

    private static function storeFormStep(string $stepName,
            Request $request): void
    {
        $inputs = session(self::$sessionDataName, []);
        $inputs[$stepName] = $request->all();
        session([self::$sessionDataName => $inputs]);
    }
}
