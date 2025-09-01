<?php

namespace App\Services;

use App\Rules\MaxText;
use App\Models\Course;
use App\Models\Event;
use App\Models\StudentYear;
use App\Models\EventStudent;
use App\Models\EventEvaluation;

class EvalFormStep extends MultiStepForm
{
    protected static string $startRoute = 'events.eval-form.consent.create';
    protected static string $endRoute = 'events.eval-form.end';
    protected static string $endView = 'eval-form.end';
    protected static string $sessionInputName = 'evalFormInputs';
    protected static string $formTitle = 'Feedback Form';
    protected static Event $event;

    protected static function setViewsData(): void
    {
        static::$viewsData = [
            'programs' => Course::all(),
            'yearLevels' => StudentYear::all(),
            'formTitle' => static::$formTitle
        ];
    }

    public static function store(Event $event): void
    {
        static::$event = $event;
        $inputs = session(static::$sessionInputName, []);
        $studentInput = $inputs['events.eval-form.identity'];
        $studentId = $studentInput['student_id'];
        $student = EventStudent::whereHas('eventDate.event', 
            function ($query) use ($event) {
                $query->whereKey($event->id);
            })->where('student_id', $studentId)->first();
        if (!$student) {
            static::setEndViewData();
            return;
        }
        $attendee = $student->eventAttended;
        $evalInput = $inputs['events.eval-form.evaluation'];
        $eval = new EventEvaluation;
        $eval->event()->associate($event);
        $eval->attendee()->associate($attendee);
        $eval->overall_satisfaction = $evalInput['overall_satisfaction'];
        $eval->content_relevance = $evalInput['content_relevance'];
        $eval->speaker_effectiveness = $evalInput['speaker_effectiveness'];
        $eval->engagement_level = $evalInput['engagement_level'];
        $eval->duration = $evalInput['duration'];
        $eval->topics_covered = $evalInput['topics_covered'];
        $eval->suggestions_for_improvement = $evalInput['suggestions_for_improvement'];
        $eval->future_topics = $evalInput['future_topics'];
        $eval->overall_experience = $evalInput['overall_experience'];
        $eval->additional_comments = $evalInput['additional_comments'];
        $eval->selected = false;
        $eval->save();
        static::setEndViewData();
    }
    
    protected static function setEndViewData(): void
    {
        static::$endViewData = [
            'formTitle' => static::$formTitle
        ];
    }

    protected static function setRoutes(): void
    {
        static::$routes = [
            'events.eval-form.consent' => [
                'view' => 'eval-form.consent',
                'rules' => [
                    'consent' => ['required', 'accepted']
                ]
            ], 
            'events.eval-form.identity' => [
                'view' => 'eval-form.identity',
                'rules' => [
                    /*
                    'first_name' => ['required', 'max:50'],
                    'middle_name' => ['max:50'],
                    'last_name' => ['required', 'max:50'],
                    'suffix_name' => ['max:10'],
                    */
                    'student_id' => ['required', 'max:20'],
                    /*
                    'program' => ['required', 'integer', 'exists:courses,id'],
                    'year_level' => ['required', 'integer', 
                        'exists:student_years,id']
                    */
                ]
            ], 
            'events.eval-form.evaluation' => [
                'view' => 'eval-form.evaluation',
                'rules' => [
                    'overall_satisfaction' => ['required', 'integer', 'min:1',
                        'max:5'],
                    'content_relevance' => ['required', 'integer', 'min:1', 
                        'max:5'],
                    'speaker_effectiveness' => ['required', 'integer', 'min:1',
                        'max:5'],
                    'engagement_level' => ['required', 'integer', 'min:1', 
                        'max:5'],
                    'duration' => ['required', 'integer', 'min:1', 'max:3'],
                    'topics_covered' => ['required', new MaxText],
                    'suggestions_for_improvement' => ['required', new MaxText],
                    'future_topics' => ['required', new MaxText],
                    'overall_experience' => ['required', new MaxText],
                    'additional_comments' => [new MaxText]
                ]
            ], 
            'events.eval-form.acknowledgement' => [
                'view' => 'eval-form.acknowledgement',
                'rules' => []
            ], 
        ];
    }
}
