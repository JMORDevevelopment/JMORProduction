<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandGuideline extends Model
{
    // NOTE: schema assumed from ContentPageService usage (link, meta_title,
    // meta_description, meta_keywords, name). Confirm real columns/PK with
    // `DESCRIBE brand_guidelines;` before relying on this elsewhere.
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
