<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryRadioShow extends Model
{
    protected $table = 'category_radio_show';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'menu_status',
        'parent_id',
        'sub_title',
        'description',
        'image',
        'link',
        'published',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function shows()
    {
        return $this->hasMany(RadioShow::class, 'category_id', 'id');
    }
}
