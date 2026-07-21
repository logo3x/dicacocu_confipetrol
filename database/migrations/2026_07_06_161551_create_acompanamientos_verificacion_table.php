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
        Schema::create('acompanamientos_verificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();
            $table->date('fecha_ejecucion');
            $table->string('campo')->nullable();
            $table->string('area')->nullable();
            $table->foreignId('responsable_area_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo_verificacion')->comment('verificacion_cumplimiento_do|inspeccion_gerencial_caminar_planta');

            // Modalidad binaria: ambos evaluadores son obligatorios.
            $table->foreignId('evaluador_operativo_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('evaluador_hseq_id')->constrained('users')->cascadeOnDelete();

            $table->json('pasos_observados')->nullable()->comment('paso a paso de la actividad observada, lista de items');
            $table->text('hallazgos')->nullable();
            $table->text('plan_accion')->nullable();

            $table->unsignedTinyInteger('puntaje_opt')->nullable()->comment('0-100. >=95 excelente, 80-94 bueno, 60-79 regular, <60 deficiente');
            $table->string('clasificacion_opt')->nullable();

            $table->boolean('actividad_detenida')->default(false)->comment('autoridad del evaluador para detener por riesgo critico, independiente del puntaje');
            $table->text('motivo_detencion')->nullable();

            $table->timestamp('cerrado_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['actividad_id', 'fecha_ejecucion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acompanamientos_verificacion');
    }
};
