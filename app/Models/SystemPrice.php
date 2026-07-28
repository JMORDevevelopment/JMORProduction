<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemPrice extends Model
{
    // NOTE: schema assumed from PackageService usage (package_id, from_qty,
    // to_qty, system_price). The legacy table name has no trailing `s`;
    // confirm real columns/PK with `DESCRIBE system_price;`.
    protected $table = 'system_price';

    public $timestamps = false;

    protected $fillable = [
        'package_id',
        'from_qty',
        'to_qty',
        'system_price',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
