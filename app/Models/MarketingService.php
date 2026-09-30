<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The original CI app calls these "Marketing Services" (admin sidebar);
 * they back the checkbox list on the talk show guest checkout page.
 * Table name is `services` (plural) — distinct from the `service` table.
 */
class MarketingService extends Model
{
    protected $table = 'services';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'question',
        'product_code',
        'description',
        'price',
    ];
}
