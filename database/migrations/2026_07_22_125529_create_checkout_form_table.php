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
        Schema::create('checkout_form', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100);
            $table->string('placeholder', 100);
            $table->integer('required');
            $table->string('label', 100);
            $table->integer('types');
            $table->integer('form_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_form');
    }
};
