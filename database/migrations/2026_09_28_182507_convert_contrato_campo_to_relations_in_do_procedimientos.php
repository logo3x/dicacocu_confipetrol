<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Contrato y campo pasan de texto libre a relaciones con sus catálogos.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Los registros previos usaban texto libre y no tienen catálogo al que apuntar.
        DB::table('do_procedimientos')->delete();

        // SQLite no permite soltar una columna que participa en un índice.
        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->dropIndex('do_procedimientos_anio_ciclo_contrato_index');
        });

        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->dropColumn(['contrato', 'campo']);
        });

        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->foreignId('contrato_id')->after('anio_ciclo')->constrained('do_contratos')->restrictOnDelete();
            $table->foreignId('campo_id')->after('contrato_id')->constrained('do_campos')->restrictOnDelete();
            $table->index(['anio_ciclo', 'contrato_id']);
        });
    }

    public function down(): void
    {
        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->dropIndex(['anio_ciclo', 'contrato_id']);
            $table->dropConstrainedForeignId('contrato_id');
            $table->dropConstrainedForeignId('campo_id');
        });

        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->string('contrato')->after('anio_ciclo');
            $table->string('campo')->after('contrato');
            $table->index(['anio_ciclo', 'contrato']);
        });
    }
};
