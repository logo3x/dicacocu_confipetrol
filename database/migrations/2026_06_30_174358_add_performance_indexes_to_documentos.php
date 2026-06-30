<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            // Índice compuesto para filtros frecuentes del dashboard
            $table->index(['estado', 'deleted_at'], 'idx_documentos_estado_deleted');

            // Índice para documentos próximos a vencer
            $table->index(['fecha_vencimiento', 'estado'], 'idx_documentos_vencimiento_estado');

            // Índice para filtrar por fase en reportes
            $table->index(['fase_dicacocu', 'estado'], 'idx_documentos_fase_estado');

            // Índice para listado por creador
            $table->index(['created_by', 'estado'], 'idx_documentos_creator_estado');
        });

        Schema::table('lectura_documentos', function (Blueprint $table) {
            $table->index(['documento_id', 'user_id', 'confirmado'], 'idx_lecturas_doc_user_confirmado');
        });

        Schema::table('curso_inscripciones', function (Blueprint $table) {
            $table->index(['curso_id', 'estado'], 'idx_inscripciones_curso_estado');
            $table->index(['user_id', 'estado'], 'idx_inscripciones_user_estado');
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropIndex('idx_documentos_estado_deleted');
            $table->dropIndex('idx_documentos_vencimiento_estado');
            $table->dropIndex('idx_documentos_fase_estado');
            $table->dropIndex('idx_documentos_creator_estado');
        });

        Schema::table('lectura_documentos', function (Blueprint $table) {
            $table->dropIndex('idx_lecturas_doc_user_confirmado');
        });

        Schema::table('curso_inscripciones', function (Blueprint $table) {
            $table->dropIndex('idx_inscripciones_curso_estado');
            $table->dropIndex('idx_inscripciones_user_estado');
        });
    }
};
