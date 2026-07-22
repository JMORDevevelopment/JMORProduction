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
        Schema::create('order_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('item', 100)->nullable();
            $table->string('type', 80)->nullable();
            $table->integer('qty')->nullable();
            $table->float('price', 10)->nullable();
            $table->float('sub_total', 10)->nullable();
            $table->integer('order_id')->nullable();
            $table->date('date_added')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
