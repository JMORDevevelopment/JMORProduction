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
     * Bounded to 4 levels deep, matching the original CI header.php exactly
     * (main_nav -> sub -> sub_child -> sub_child_menu). Unbounded self-referential
     * eager loading is NOT used here because the `menu` table has a cycle somewhere
     * in its parent_id chain, which caused infinite recursive eager loading and a
     * 30-second timeout. Run this to find and fix the cyclic row(s):
     *
     *   mysql -u root -proot jmor_web -e "
     *     SELECT id, parent_id, title FROM menu ORDER BY parent_id, id;"
     *
     * and check for any parent_id that (directly or via its own chain) points back
     * down to one of its own descendants.
     */
    public function childrenRecursive()
    {
        return $this->children()
            ->with(['children' => function ($q) {
                $q->with('children');
            }]);
    }

    /**
     * Convenience scope for fetching the full top-level nav tree in one query,
     * capped at 4 levels deep.
     */
    public static function tree()
    {
        // TEMPORARY DIAGNOSTIC — remove once the repeated-call bug is found.
        static $callCount = 0;
        $callCount++;
        \Log::info("Menu::tree() called - count: {$callCount}", [
            'trace' => collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 30))
                ->map(fn($t) => ($t['class'] ?? '') . ($t['type'] ?? '') . $t['function'] . ':' . ($t['line'] ?? '?'))
                ->implode(' <- '),
        ]);
        if ($callCount > 5) {
            abort(500, "Menu::tree() called {$callCount} times - loop confirmed, check laravel.log for the trace.");
        }

        return static::where('parent_id', 0)
            ->orderBy('position', 'asc')
            ->with('childrenRecursive')
            ->get();
    }
}