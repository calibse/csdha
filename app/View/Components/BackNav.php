<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BackNav extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
		public string $backRoute,
		public string $backName,
		public array $routeParams = []
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.back-nav');
    }
}
