<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    // NOTE: schema assumed from ContentPageService usage (link, meta_title,
    // meta_description, meta_keywords, name). Confirm real columns/PK with
    // `DESCRIBE press_releases;` before relying on this elsewhere.
    protected $table = 'press_releases';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
