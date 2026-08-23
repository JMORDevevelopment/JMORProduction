<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'position',
        'group_id',
        'menu_type',
        'page_id',
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')
            ->orderBy('position', 'asc');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function group()
    {
        return $this->belongsTo(MenuGroup::class, 'group_id');
    }

    public static function tree()
    {
        return static::where('parent_id', 0)
            ->orderBy('position', 'asc')
            ->with('childrenRecursive')
            ->get();
    }
}
