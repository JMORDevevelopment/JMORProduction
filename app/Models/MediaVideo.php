<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaVideo extends Model
{
    // NOTE: schema assumed from ContentPageService usage (link, meta_title,
    // meta_description, meta_keywords, name). Confirm real columns/PK with
    // `DESCRIBE media_video;` before relying on this elsewhere.
    protected $table = 'media_video';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
