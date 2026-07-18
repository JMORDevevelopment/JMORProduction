<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    // NOTE: schema assumed from slider_section.php usage (slider_image, slider_name,
    // slider_link, priority). Run this to confirm the real columns/PK before relying on it:
    //   mysql -u root -proot jmor_web -e "DESCRIBE slider;"
    protected $table = 'slider';

    public $timestamps = false;

    protected $fillable = [
        'slider_image',
        'slider_name',
        'slider_link',
        'priority',
    ];
}