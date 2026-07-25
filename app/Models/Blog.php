<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    // NOTE: schema assumed from SearchService/ContentPageService usage
    // (name, link, meta_title, meta_description, meta_keywords). Confirm
    // real columns/PK with `DESCRIBE blog;` before relying on this elsewhere.
    protected $table = 'blog';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
