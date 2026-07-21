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
        Schema::create('inspecciones_gerenciales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acompanamiento_verificacion_id')
                ->unique()
                ->constrained('acompanamientos_verificacion')
                ->cascadeOnDelete();
            $table->text('hallazgos_positivos')->nullable();
            $table->text('desvios_oportunidades_mejora')->nullable();
            $table->timestamps();
        });

        Schema::create('inspeccion_gerencial_reglas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspeccion_gerencial_id')->constrained('inspecciones_gerenciales')->cascadeOnDelete();
            // Denormalizado a propósito: HasManyThrough no soporta create()/update() de forma
            // fiable en Eloquent/Filament (no hay FK directa al padre inmediato). Esta columna
            // permite un HasMany real desde AcompanamientoVerificacion.
            $table->foreignId('acompanamiento_verificacion_id')
                ->constrained('acompanamientos_verificacion', indexName: 'insp_ger_reglas_acomp_verif_id')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('numero_regla')->comment('1 al 12, ver App\\Enums\\ReglaSalvaVidas');
            $table->string('cumple')->nullable()->comment('si|no|na');
            $table->timestamps();

            $table->unique(['inspeccion_gerencial_id', 'numero_regla'], 'insp_ger_reglas_unique');
        });

        Schema::create('inspeccion_gerencial_acciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspeccion_gerencial_id')->constrained('inspecciones_gerenciales')->cascadeOnDelete();
            $table->foreignId('acompanamiento_verificacion_id')
                ->constrained('acompanamientos_verificacion', indexName: 'insp_ger_acciones_acomp_verif_id')
                ->cascadeOnDelete();
            $table->text('accion');
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_cierre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspeccion_gerencial_acciones');
        Schema::dropIfExists('inspeccion_gerencial_reglas');
        Schema::dropIfExists('inspecciones_gerenciales');
    }
};
