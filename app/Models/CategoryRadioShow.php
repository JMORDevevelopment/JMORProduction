<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryRadioShow extends Model
{
    protected $table = 'category_radio_show';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'image',
        'link',
        'menu_status',
        'parent_id',
        'published',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function shows(): HasMany
    {
        return $this->hasMany(RadioShow::class, 'category_id', 'id');
    }
}
