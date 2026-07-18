<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeTab extends Model
{
    protected $table = 'home_tab';

    protected $primaryKey = 'tab_id';

    public $timestamps = false;

    protected $fillable = [
        'tab_title',
        'tab_description',
        'tab_list',
        'benefits',
        'cost',
    ];
}