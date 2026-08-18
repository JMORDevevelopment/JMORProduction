<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    protected $table = 'press_releases';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'description',
        'image',
        'published',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
