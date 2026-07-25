<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryRadioShow extends Model
{

    protected $table = 'category_radio_show';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'link',
        'parent_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
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
