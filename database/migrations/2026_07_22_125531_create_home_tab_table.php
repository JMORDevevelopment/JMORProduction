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
        Schema::create('home_tab', function (Blueprint $table) {
            $table->integer('tab_id', true);
            $table->string('tab_title', 100)->nullable();
            $table->text('tab_description')->nullable();
            $table->text('tab_list')->nullable();
            $table->text('benefits');
            $table->text('cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_tab');
    }
};
