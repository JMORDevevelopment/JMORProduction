<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    public $timestamps = false;

    protected $fillable = ['option', 'value'];

    /**
     * Get every setting as an ['option_name' => 'value'] array.
     * This replaces the fragile CI pattern of accessing $options[3]['value'],
     * $options[11]['value'], etc. by numeric array position.
     *
     * Cached for 10 minutes since settings rarely change.
     */
    public static function allKeyed(): array
    {
        return Cache::remember('settings.keyed', 600, function () {
            return static::query()->pluck('value', 'option')->toArray();
        });
    }

    /**
     * Get a single setting by its option key, with an optional default.
     */
    public static function get(string $option, ?string $default = null): ?string
    {
        return static::allKeyed()[$option] ?? $default;
    }
}