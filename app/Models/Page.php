<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    public $timestamps = false;

    protected $fillable = [
        'link',
        'name',
        'priority',
        'slider_status',
        'menu_location',
        'description',
        'image',
        'form_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'menu_status',
    ];
}
