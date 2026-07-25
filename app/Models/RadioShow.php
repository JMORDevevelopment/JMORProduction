<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadioShow extends Model
{
    // NOTE: schema assumed from RadioShowService/ContentPageService/SearchService
    // usage (name, link, show_date, category_id, meta_title, meta_description,
    // meta_keywords). Confirm real columns/PK with `DESCRIBE radio_show;`.
    protected $table = 'radio_show';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'link',
        'show_date',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryRadioShow::class, 'category_id', 'id');
    }
}
