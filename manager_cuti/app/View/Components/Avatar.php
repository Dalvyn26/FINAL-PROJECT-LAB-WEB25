<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Avatar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $user;
    public $size;

    public function __construct($user, $size = 'w-10 h-10')
    {
        $this->user = $user;
        $this->size = $size ?: 'w-10 h-10';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.avatar');
    }
}