<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommended extends Model
{
    // NOTE: schema assumed from ContentPageService usage (link, meta_title,
    // meta_description, meta_keywords, name). Confirm real columns/PK with
    // `DESCRIBE recommended;` before relying on this elsewhere.
    protected $table = 'recommended';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
