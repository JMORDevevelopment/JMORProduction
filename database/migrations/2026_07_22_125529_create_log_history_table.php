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
        Schema::create('log_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('ip', 45);
            $table->timestamp('log_time')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('user')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_history');
    }
};
