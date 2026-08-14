<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadioShow extends Model
{
    protected $table = 'radio_show';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'description',
        'show_date',
        'category_id',
        'image',
        'published',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryRadioShow::class, 'category_id', 'id');
    }
}
