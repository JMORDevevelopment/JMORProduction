<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->tinyInteger('id', true, true);
            $table->tinyInteger('parent_id')->unsigned()->default(0);
            $table->string('title', 255)->default('');
            $table->string('url', 255)->default('');
            $table->tinyInteger('position')->unsigned()->default(0);
            $table->tinyInteger('group_id')->unsigned()->default(1);
            $table->text('menu_type');
            $table->integer('page_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
