<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MultiStepForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $eventName,
        public string $formTitle,
        public $title = null,
        public $previousStepRoute = null,
        public bool $lastStep = false,
        public bool $end = false
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.multi-step-form');
    }
}
