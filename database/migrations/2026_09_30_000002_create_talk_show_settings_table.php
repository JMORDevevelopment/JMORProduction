<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talk_show_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('price');
            $table->text('question');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talk_show_settings');
    }
};
