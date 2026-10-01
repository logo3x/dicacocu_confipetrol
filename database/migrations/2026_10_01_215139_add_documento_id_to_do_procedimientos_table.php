<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enlaza el procedimiento de la matriz DICACOCU con el documento del
     * repositorio, para dejar de escribir a mano dónde está el archivo.
     */
    public function up(): void
    {
        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->foreignId('documento_id')
                ->nullable()
                ->after('ubicacion_acceso')
                // Si se borra el documento el procedimiento sigue existiendo:
                // solo pierde el enlace.
                ->constrained('documentos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('documento_id');
        });
    }
};
