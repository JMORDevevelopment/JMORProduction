<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id('category_id');
            $table->integer('priority')->default(0);
            $table->string('name', 30)->nullable();
            $table->text('link');
            $table->integer('menu_status')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
