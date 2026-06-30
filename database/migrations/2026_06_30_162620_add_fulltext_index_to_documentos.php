<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite (entorno de test) no soporta FULLTEXT — se omite
        if (! $this->isMySQL()) {
            return;
        }

        Schema::table('documentos', function (Blueprint $table) {
            $table->fullText(['titulo', 'descripcion', 'codigo'], 'documentos_fulltext');
        });
    }

    public function down(): void
    {
        if (! $this->isMySQL()) {
            return;
        }

        Schema::table('documentos', function (Blueprint $table) {
            $table->dropFullText('documentos_fulltext');
        });
    }

    private function isMySQL(): bool
    {
        return in_array(
            config('database.default'),
            ['mysql', 'mariadb'],
            true
        );
    }
};
