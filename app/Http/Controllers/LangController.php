<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\RedirectResponse;

class LangController extends Controller
{
    /**
     * Switch the active language (original: Lang::change()).
     * Unknown names are ignored, mirroring the original endpoint's
     * redirect-to-home behavior without storing garbage in the session.
     */
    public function change(string $name): RedirectResponse
    {
        if (Language::where('name', $name)->exists()) {
            session(['lang' => $name]);
        }

        return redirect()->route('home');
    }
}
