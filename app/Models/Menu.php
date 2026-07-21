<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    public $timestamps = false;

    protected $fillable = ['parent_id', 'title', 'url', 'position'];

    /**
     * Direct children of this menu item, ordered the same way the CI app did.
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')
            ->orderBy('position', 'asc');
    }

    /**
     * Eager-loads children recursively. The CI original hardcoded 4 levels
     * (main_nav -> sub -> sub_child -> sub_child_menu); this recurses
     * unbounded, but live data never exceeds depth 4 (verified against the
     * menu table dump), so behavior matches exactly today. Two rows have a
     * parent_id that doesn't exist in the table (3 and 110) — these are
     * orphaned/unreachable in both the old and new stack, not a bug.
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Full top-level nav tree in one query.
     */
    public static function tree()
    {
        return static::where('parent_id', 0)
            ->orderBy('position', 'asc')
            ->with('childrenRecursive')
            ->get();
    }
}