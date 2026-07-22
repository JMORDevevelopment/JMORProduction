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
        Schema::create('transaction', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id');
            $table->string('order_type', 150);
            $table->text('checkout_type');
            $table->string('transaction_id', 20);
            $table->string('auth_code', 30);
            $table->integer('user_id');
            $table->float('amount', 10);
            $table->timestamp('published')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
