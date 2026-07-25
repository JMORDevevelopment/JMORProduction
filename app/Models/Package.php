<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    // NOTE: schema assumed from PackageService usage (name, description,
    // category_name, priority, discount). Confirm real columns/PK with
    // `DESCRIBE packages;`.
    protected $table = 'packages';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'category_name',
        'priority',
        'discount',
    ];

    public function serverPrices()
    {
        return $this->hasMany(PackagePrice::class, 'package_id');
    }

    public function systemPrices()
    {
        return $this->hasMany(SystemPrice::class, 'package_id');
    }
}
