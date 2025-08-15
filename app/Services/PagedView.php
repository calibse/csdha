<?php

namespace App\Services;

use Illuminate\Contracts\Support\Renderable;

class PagedView implements Renderable
{
    private string $view;
    private array $data;

    public function __construct(string $view, array $data = null)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function render(): string
    {
        return view($this->view, $this->data)->render();
    }
}
