<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $previousMode = $this->relaxSqlMode();

        try {
            Schema::table('user', function (Blueprint $table) {
                $table->string('password', 255)->change();
            });
        } finally {
            $this->restoreSqlMode($previousMode);
        }
    }

    public function down(): void
    {
        $previousMode = $this->relaxSqlMode();

        try {
            Schema::table('user', function (Blueprint $table) {
                $table->string('password', 32)->change();
            });
        } finally {
            $this->restoreSqlMode($previousMode);
        }
    }

    /**
     * Legacy rows store zero dates (0000-00-00), which strict mode rejects
     * while a column change rebuilds and revalidates the table. Relax the
     * session sql_mode for the duration of the change only.
     */
    private function relaxSqlMode(): string
    {
        $previousMode = (string) DB::selectOne('SELECT @@SESSION.sql_mode AS mode')->mode;

        DB::statement("SET SESSION sql_mode = ''");

        return $previousMode;
    }

    private function restoreSqlMode(string $mode): void
    {
        $safeMode = preg_replace('/[^A-Za-z0-9_,]/', '', $mode);

        DB::statement("SET SESSION sql_mode = '{$safeMode}'");
    }
};
