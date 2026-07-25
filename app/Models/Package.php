<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{

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
