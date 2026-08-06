<?php

namespace App\View\Components;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.footer', [
            'topSettings' => Setting::orderBy('id')->get(),
        ]);
    }
}
