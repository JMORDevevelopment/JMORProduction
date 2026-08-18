<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaResource extends Model
{
    protected $table = 'media_resouces';

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
