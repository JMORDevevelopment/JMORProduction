<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'language';

    protected $primaryKey = 'language_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'image',
        'sort_order',
    ];
}
