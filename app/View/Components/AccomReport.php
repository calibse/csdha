<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccomReport extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $events,
        public $editors,
        public $approved,
        public $president
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.accom-report');
    }
}
