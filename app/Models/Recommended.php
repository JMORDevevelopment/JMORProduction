<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommended extends Model
{
    protected $table = 'recommended';

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
