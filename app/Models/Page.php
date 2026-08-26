<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'description',
        'image',
        'priority',
        'slider_status',
        'menu_location',
        'form_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'menu_status',
    ];
}
