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
        Schema::create('slider', function (Blueprint $table) {
            $table->integer('slider_id', true);
            $table->string('slider_name', 100)->nullable();
            $table->text('slider_desc')->nullable();
            $table->text('slider_image')->nullable();
            $table->integer('priority')->default(0);
            $table->text('slider_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider');
    }
};
