<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaVideo extends Model
{
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
