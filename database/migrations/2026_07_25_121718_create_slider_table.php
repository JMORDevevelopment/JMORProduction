<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->increments('slider_id');
            $table->string('slider_name', 100)->nullable();
            $table->text('slider_desc')->nullable();
            $table->text('slider_image')->nullable();
            $table->integer('priority')->default(0);
            $table->text('slider_link');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slider');
    }
};
