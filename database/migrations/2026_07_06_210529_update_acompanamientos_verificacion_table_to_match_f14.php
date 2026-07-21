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
        Schema::table('acompanamientos_verificacion', function (Blueprint $table) {
            // Renombrar según roles reales del formato: Observador (quien visita/evalúa)
            // y Acompañante (opcional). Ya no es una modalidad binaria estricta.
            $table->renameColumn('evaluador_operativo_id', 'observador_id');
            $table->renameColumn('evaluador_hseq_id', 'acompanante_id');
        });

        Schema::table('acompanamientos_verificacion', function (Blueprint $table) {
            $table->string('cargo_observador')->nullable()->after('observador_id');
            $table->string('cargo_acompanante')->nullable()->after('acompanante_id');

            // Checklist de 12 preguntas (items 1-11 valen 6.37% c/u si es SI; item 12 vale 30%).
            $table->boolean('q1_procedimiento_disponible')->nullable();
            $table->boolean('q2_usa_epp_correctamente')->nullable();
            $table->boolean('q3_identifica_peligros_riesgos')->nullable();
            $table->boolean('q4_herramientas_disponibles')->nullable();
            $table->boolean('q5_area_limpia_ordenada')->nullable();
            $table->boolean('q6_aplica_controles')->nullable();
            $table->boolean('q7_procedimiento_actualizado')->nullable();
            $table->boolean('q8_procedimiento_facil_entendimiento')->nullable();
            $table->boolean('q9_procedimiento_divulgado')->nullable();
            $table->boolean('q10_personal_capacitado_certificado')->nullable();
            $table->boolean('q11_personal_mostro_habilidad')->nullable();

            // Pregunta 12: comparación de pasos (vale 30% si coinciden, 0 si no).
            $table->unsignedTinyInteger('pasos_segun_procedimiento')->nullable();
            $table->unsignedTinyInteger('pasos_en_observacion')->nullable();

            $table->string('analisis_actividad')->nullable()
                ->comment('sigue_correctamente|sigue_debe_mejorar|mejorar_procedimiento|difusion_reentrenamiento|fortalecer_do|suspension_tareas');

            $table->renameColumn('puntaje_opt', 'puntaje_opt_calculado');
        });

        Schema::table('acompanamientos_verificacion', function (Blueprint $table) {
            $table->decimal('puntaje_opt_calculado', 5, 2)->nullable()->change();
            $table->foreignId('acompanante_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acompanamientos_verificacion', function (Blueprint $table) {
            $table->renameColumn('puntaje_opt_calculado', 'puntaje_opt');
            $table->renameColumn('observador_id', 'evaluador_operativo_id');
            $table->renameColumn('acompanante_id', 'evaluador_hseq_id');

            $table->dropColumn([
                'cargo_observador',
                'cargo_acompanante',
                'q1_procedimiento_disponible',
                'q2_usa_epp_correctamente',
                'q3_identifica_peligros_riesgos',
                'q4_herramientas_disponibles',
                'q5_area_limpia_ordenada',
                'q6_aplica_controles',
                'q7_procedimiento_actualizado',
                'q8_procedimiento_facil_entendimiento',
                'q9_procedimiento_divulgado',
                'q10_personal_capacitado_certificado',
                'q11_personal_mostro_habilidad',
                'pasos_segun_procedimiento',
                'pasos_en_observacion',
                'analisis_actividad',
            ]);
        });
    }
};
