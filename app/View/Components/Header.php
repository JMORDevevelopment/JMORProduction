<?php

namespace App\View\Components;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.header', [
            'topSettings' => Setting::orderBy('id')->get(),
            'navigation' => Menu::tree(),
        ]);
    }
}
