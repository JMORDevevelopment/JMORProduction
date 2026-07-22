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
        Schema::create('package_price', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id');
            $table->float('pack_price');
            $table->integer('from_qty');
            $table->integer('to_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_price');
    }
};
