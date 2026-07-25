<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    // NOTE: schema not exercised beyond a plain `->get()` in SignUpController,
    // so no columns are asserted here. Confirm real columns/PK with
    // `DESCRIBE region;`.
    protected $table = 'region';

    public $timestamps = false;
}
