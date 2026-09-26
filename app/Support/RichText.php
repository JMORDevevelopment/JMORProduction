<?php

namespace App\Support;

use Illuminate\Support\HtmlString;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Output-time sanitisation for legacy rich-text fields (M4).
 *
 * Admin-entered description fields are stored as HTML and rendered with
 * {!! !!} across the frontend. Instead of changing every field to plain
 * text (which would strip existing formatting), every such render passes
 * through here: safe markup is preserved, scripts, event handlers and
 * dangerous URL schemes are removed at render time — so stored payloads
 * in the database can never execute.
 */
class RichText
{
    private static ?HtmlSanitizer $sanitizer = null;

    public static function sanitize(?string $html): HtmlString
    {
        if ($html === null || $html === '') {
            return new HtmlString('');
        }

        return new HtmlString(self::sanitizer()->sanitize($html));
    }

    private static function sanitizer(): HtmlSanitizer
    {
        return self::$sanitizer ??= new HtmlSanitizer(
            (new HtmlSanitizerConfig)->allowSafeElements()
        );
    }
}
