<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Formato de Acompañamiento y Verificación de Actividades (HSEQ-GCA1-F-14).
 * Parte 1: verificación de Disciplina Operativa con puntaje OPT.
 * Parte 2: inspección gerencial "Caminar la Planta" (opcional).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('do_evaluaciones_f14', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedimiento_id')->constrained('do_procedimientos')->cascadeOnDelete();

            // Encabezado
            $table->date('fecha_ejecucion');
            $table->string('campo')->nullable();
            $table->string('area')->nullable();
            $table->foreignId('responsable_area_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre_actividad_observada');
            $table->foreignId('observador_id')->constrained('users')->cascadeOnDelete();
            $table->string('cargo_observador');
            $table->foreignId('acompanante_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cargo_acompanante')->nullable();

            // Observación de la actividad ejecutada (hasta 14 pasos)
            $table->json('pasos_observados')->nullable();

            // Checklist: 11 preguntas de 6.37% cada una
            foreach (range(1, 11) as $n) {
                $table->boolean("q{$n}")->default(false);
                $table->text("q{$n}_observacion")->nullable();
            }

            // Pregunta 12: coincidencia de pasos (30%)
            $table->unsignedSmallInteger('pasos_segun_procedimiento')->nullable();
            $table->unsignedSmallInteger('pasos_en_observacion')->nullable();

            $table->decimal('subtotal', 5, 2)->default(0);
            $table->decimal('puntaje_opt', 5, 2)->default(0);
            $table->string('criterio_opt')->default('sin_datos');

            $table->text('oportunidades_mejora')->nullable();
            $table->text('analisis_actividad')->nullable();

            // Parte 2 — Inspección gerencial
            $table->boolean('aplica_inspeccion_gerencial')->default(false);
            $table->text('hallazgos_positivos')->nullable();
            $table->text('desvios_oportunidades')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['procedimiento_id', 'fecha_ejecucion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('do_evaluaciones_f14');
    }
};
