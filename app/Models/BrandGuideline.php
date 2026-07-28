<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandGuideline extends Model
{
    protected $table = 'brand_guidelines';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
