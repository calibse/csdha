<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventEvaluation;
use App\Mail\EventEvaluation as EventEvaluationMail;
use App\Models\EventStudent;
use App\Models\Course;
use App\Models\StudentYear;
use App\Services\Format;
use App\Http\Requests\StoreEventEvalIdentityRequest;
use App\Http\Requests\StoreEventEvalRequest;
use App\Http\Requests\StoreConsentRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EventEvaluationController extends Controller
{
    private static string $sessionDataName;

    public function __construct()
    {
        self::$sessionDataName = 'event_evaluation';
    }

    public function editConsentStep(Request $request, Event $event)
    {
        $isPreview = self::isPreview($request);
        if ($isPreview) {
            session()->forget(self::$sessionDataName);
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        $inputs = session(self::$sessionDataName, []);
        return view('event-evaluations.consent', [
            'step' => 0,
            'submitRoute' => route($routeNamePrefix . 'consent.store', [
                'event' => $event->public_id,
                'token' => $request->token
            ]),
            'token' => $request->token,
            'isPreview' => $isPreview,
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event, $request->token, $isPreview));
    }

    public function storeConsentStep(StoreConsentRequest $request,
            Event $event)
    {
        $isPreview = self::isPreview($request);
        if ($isPreview) {
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route($routeNamePrefix . 'evaluation.edit', [
            'event' => $event->public_id,
            'token' => $request->token
        ])->withFragment('content');
    }

    public function editEvaluationStep(Request $request, Event $event)
    {
        $isPreview = self::isPreview($request);
        if ($isPreview) {
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        $inputs = session(self::$sessionDataName, []);
        return view('event-evaluations.evaluation', [
            'step' => 1,
            'previousStepRoute' => route($routeNamePrefix . 'consent.edit', [
                'event' => $event->public_id,
            ]),
            'submitRoute' => route($routeNamePrefix . 'evaluation.store', [
                'event' => $event->public_id,
                'token' => $request->token
            ]),
            'token' => $request->token,
            'isPreview' => $isPreview,
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event, $request->token, $isPreview));
    }

    public function storeEvaluationStep(StoreEventEvalRequest $request,
            Event $event)
    {
        $isPreview = self::isPreview($request);
        if ($isPreview) {
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route($routeNamePrefix . 'acknowledgement.edit', [
            'event' => $event->public_id,
            'token' => $request->token
        ])->withFragment('content');
    }

    public function editAcknowledgementStep(Request $request, Event $event)
    {
        $isPreview = self::isPreview($request);
        if ($isPreview) {
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        $inputs = session(self::$sessionDataName, []);
        return view('event-evaluations.acknowledgement', [
            'step' => 2,
            'previousStepRoute' => route($routeNamePrefix . 'evaluation.edit',
                [
                'event' => $event->public_id,
            ]),
            'submitRoute' => route($routeNamePrefix . 'acknowledgement.store',
                [
                'event' => $event->public_id,
                'token' => $request->token
            ]),
            'lastStep' => true,
            'token' => $request->token,
            'isPreview' => $isPreview,
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event, $request->token, $isPreview));
    }

    public function storeAcknowledgementStep(Request $request, Event $event)
    {
        $isPreview = self::isPreview($request);
        if ($isPreview) {
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route($routeNamePrefix . 'end.show', [
            'event' => $event->public_id,
            'token' => $request->token
        ])->withFragment('content');
    }

    public function showEndStep(Request $request, Event $event)
    {
        $isPreview = self::isPreview($request);
        if ($isPreview) {
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        self::store($event, $isPreview);
        if (!$isPreview) {
            session()->forget(self::$sessionDataName);
        }
        return view('event-evaluations.end', [
            'step' => 3,
            'isPreview' => $isPreview,
            'end' => true
        ] + self::multiFormData($event, $request->token, $isPreview));
    }

    private static function store(Event $event, bool $isPreview): void
    {
        if ($isPreview) return;
        $inputs = session(self::$sessionDataName, []);
        $evalInput = $inputs['events.evaluations.evaluation'];
        $eval = new EventEvaluation;
        $eval->event()->associate($event);
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
        $eval->save();
        self::deleteToken($evalInput['token']);
    }

    private static function deleteToken($rawToken): void
    {
        DB::table('event_evaluation_tokens')
            ->where('token', hash('sha256', $rawToken))->delete();
    }

    private static function multiFormData(Event $event, string $token = null, 
        bool $isPreview): array
    {
        if ($isPreview) {
            $routeNamePrefix = 'events.evaluations-preview.';
        } else {
            $routeNamePrefix = 'events.evaluations.';
        }
        $routes = [
            route($routeNamePrefix . 'consent.edit', [
                'event' => $event->public_id,
                'token' => $token
            ]) . '#content',
            route($routeNamePrefix . 'evaluation.edit', [
                'event' => $event->public_id,
                'token' => $token
            ]) . '#content',
            route($routeNamePrefix . 'acknowledgement.edit', [
                'event' => $event->public_id,
                'token' => $token
            ]) . '#content',
            route($routeNamePrefix . 'end.show', [
                'event' => $event->public_id,
                'token' => $token
            ]) . '#content',
        ];
        $inputs = session(self::$sessionDataName, []);
        return [
            'formTitle' => 'Evaluation',
            'eventName' => $event->gpoaActivity->name,
            'event' => $event,
            'form' => $event->evalForm,
            'completeSteps' => count($inputs),
            'routes' => $routes
        ];
    }

    private static function storeFormStep(string $stepName,
            Request $request): void
    {
        $inputs = session(self::$sessionDataName, []);
        $inputs[$stepName] = $request->all();
        session([self::$sessionDataName => $inputs]);
    }

    private static function isPreview($request)
    {
        if (in_array('auth', $request->route()->gatherMiddleware())) return true;
        return false;
    }
}
