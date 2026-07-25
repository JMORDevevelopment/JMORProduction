<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // NOTE: schema assumed from SearchService/ContentPageService usage
    // (name, link, meta_title, meta_description, meta_keywords). Confirm
    // real columns/PK with `DESCRIBE pages;` before relying on this elsewhere.
    protected $table = 'pages';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
