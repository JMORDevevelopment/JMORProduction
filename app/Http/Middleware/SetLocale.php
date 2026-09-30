<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Mirrors the original CI Language library: a `lang` session value
     * (seeded from the `lang` cookie when present) selects the active
     * language; the language table maps it to a locale code.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = session('lang');

        if (empty($lang) && $request->cookie('lang')) {
            $lang = $request->cookie('lang');
            session(['lang' => $lang]);
        }

        App::setLocale($this->resolveLocale($lang ?: 'english'));

        return $next($request);
    }

    private function resolveLocale(string $name): string
    {
        try {
            return Language::where('name', $name)->first()?->code ?: 'en';
        } catch (QueryException) {
            return 'en';
        }
    }
}
