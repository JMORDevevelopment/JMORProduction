<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $primaryKey = 'category_id';

    public $timestamps = false;

    protected $fillable = [
        'priority',
        'name',
        'link',
        'menu_status',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class, 'category_name', 'link');
    }
}
