<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // The CI app's table is named `service` (singular), not the Laravel-conventional `services`.
    protected $table = 'service';

    // The CI table's primary key is `service_id`, not the Laravel-conventional `id`.
    protected $primaryKey = 'service_id';

    // The original table has no created_at / updated_at columns.
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'keywords',
        'link',
    ];
}