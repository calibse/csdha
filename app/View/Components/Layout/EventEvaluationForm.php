<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Event;

class EventEvaluationForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
            public Event $event,
            public int $step,
            public int $completeSteps,
            public array $routes,
            public bool $isPreview
        )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.event-evaluation-form');
    }
}
