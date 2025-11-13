<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Event;

class HomeFeatOngoingEvent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $next,
        public string $prev,
        public Event $model
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.home-feat-ongoing-event');
    }
}
