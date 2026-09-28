<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aplicación de las 12 Reglas que Salvan Vidas (F-14, Parte 2).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('do_evaluacion_reglas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluacion_id')->constrained('do_evaluaciones_f14')->cascadeOnDelete();
            $table->unsignedTinyInteger('numero_regla');
            $table->string('cumple')->default('na');
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->unique(['evaluacion_id', 'numero_regla']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('do_evaluacion_reglas');
    }
};
