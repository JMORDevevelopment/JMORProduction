<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'heading',
        'description',
        'image',
        'discount',
        'price',
        'upfront',
        'category_name',
        'priority',
        'status',
    ];

    public function serverPrices()
    {
        return $this->hasMany(PackagePrice::class, 'package_id');
    }

    public function systemPrices()
    {
        return $this->hasMany(SystemPrice::class, 'package_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_name', 'link');
    }
}
