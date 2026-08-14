<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RandomActsOfKindness extends Model
{
    protected $table = 'random_acts_of_kindness';

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
