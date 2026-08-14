<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $primaryKey = 'news_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'priority',
        'type',
        'description',
        'link',
        'image',
        'published',
    ];
}
