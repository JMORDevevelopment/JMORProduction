<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->float('sub_total', 10, 2)->nullable();
            $table->float('discount', 10, 2)->nullable();
            $table->float('grand_total', 10, 2)->nullable();
            $table->date('create_date')->nullable();
            $table->integer('status')->nullable();
            $table->text('checkout_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};