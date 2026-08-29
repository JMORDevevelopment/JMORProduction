<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider';

    protected $primaryKey = 'slider_id';

    public $timestamps = false;

    protected $fillable = [
        'slider_name',
        'slider_desc',
        'slider_image',
        'slider_link',
        'priority',
    ];
}
