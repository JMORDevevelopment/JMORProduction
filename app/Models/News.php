<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $table = 'news';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
