<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Matriz Integral de Disciplina Operativa (DICACOCU).
 * Cada fila representa un procedimiento recorriendo las 4 etapas DI/CA/CO/CU.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('do_procedimientos', function (Blueprint $table) {
            $table->id();

            $table->year('anio_ciclo');
            $table->string('contrato');
            $table->string('campo');

            // Etapa 1 — DI: Identificación y priorización
            $table->string('nombre_actividad');
            $table->date('fecha_identificacion');
            $table->unsignedInteger('personas_involucradas')->default(0);
            $table->boolean('amenaza_riesgo_critico')->default(false);
            $table->boolean('amenaza_equipos_criticos')->default(false);
            $table->boolean('amenaza_impacto_ambiental')->default(false);
            $table->boolean('amenaza_antecedentes')->default(false);
            $table->boolean('amenaza_afecta_servicio')->default(false);
            $table->boolean('amenaza_no_rutinaria')->default(false);
            $table->unsignedInteger('puntaje_prioridad')->default(0);
            $table->string('prioridad')->default('bajo');

            // Etapa 2 — CA: Plan de estandarización y codificación
            $table->unsignedTinyInteger('plazo_estandarizacion_meses')->default(4);
            $table->date('fecha_limite_estandarizacion')->nullable();
            $table->boolean('codificado')->default(false);
            $table->date('fecha_programada_codificacion')->nullable();
            $table->date('fecha_codificacion')->nullable();
            $table->foreignId('responsable_codificacion_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('codigo_asignado')->nullable();
            $table->string('titulo_procedimiento')->nullable();
            $table->unsignedTinyInteger('version_actual')->nullable();
            $table->string('ubicacion_acceso')->nullable();

            // Etapa 3 — CO: Comunicación
            $table->date('fecha_ultima_divulgacion')->nullable();
            $table->unsignedInteger('personas_socializadas')->nullable();
            $table->decimal('cobertura_socializacion', 5, 2)->default(0);

            // Etapa 4 — CU: Programa de verificación
            $table->unsignedTinyInteger('frecuencia_verificacion_meses')->default(12);
            $table->foreignId('responsable_area_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_programada_verificacion')->nullable();
            $table->date('fecha_ejecutada_verificacion')->nullable();
            $table->foreignId('observador_operativo_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('observador_hseq_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('puntaje_opt', 5, 2)->nullable();
            $table->string('criterio_opt')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['anio_ciclo', 'contrato']);
            $table->index('prioridad');
            $table->unique('codigo_asignado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('do_procedimientos');
    }
};
