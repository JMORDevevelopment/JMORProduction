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
        Schema::create('testimony_form', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id');
            $table->text('service_used');
            $table->text('message');
            $table->integer('status')->default(0);
            $table->timestamp('published')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimony_form');
    }
};
