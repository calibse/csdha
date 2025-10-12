<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class User extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $route = '',
        public bool $index = false,
        public bool $form = false,
        public string $title = '',
        public array $routeParams = [],
        public string $backRoute = ''
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.user');
    }
}
