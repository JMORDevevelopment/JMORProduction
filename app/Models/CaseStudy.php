<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{

    protected $table = 'case_studies';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
