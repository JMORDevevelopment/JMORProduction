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
        'priority',
        'slider_status',
        'menu_location',
        'description',
        'image',
        'form_id',
        'menu_status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
