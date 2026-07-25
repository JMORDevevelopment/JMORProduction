<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaResource extends Model
{
    // NOTE: the underlying table name is `media_resouces` (misspelled) in
    // the original CI app; kept exactly as-is here to match the live
    // database. Confirm real columns/PK with `DESCRIBE media_resouces;`.
    protected $table = 'media_resouces';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
