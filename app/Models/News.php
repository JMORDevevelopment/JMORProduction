<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    // NOTE: schema assumed from NewsService/ContentPageService usage (link,
    // meta_title, meta_description, meta_keywords, name). Confirm real
    // columns/PK with `DESCRIBE news;` before relying on $fillable elsewhere.
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
