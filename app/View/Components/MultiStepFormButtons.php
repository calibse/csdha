<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MultiStepFormButtons extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
            public ?string $previousStepRoute = null,
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
        return view('components.multi-step-form-buttons');
    }
}
