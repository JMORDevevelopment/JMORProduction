<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('parent_id')->default(0);
            $table->string('title')->default('');
            $table->string('url')->default('');
            $table->unsignedTinyInteger('position')->default(0);
            $table->unsignedTinyInteger('group_id')->default(1);
            $table->text('menu_type');
            $table->integer('page_id')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};