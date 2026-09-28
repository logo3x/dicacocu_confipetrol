<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Acciones definidas y acordadas tras la inspección gerencial (F-14, Parte 2).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('do_evaluacion_acciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluacion_id')->constrained('do_evaluaciones_f14')->cascadeOnDelete();
            $table->text('accion');
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_cierre')->nullable();
            $table->timestamp('cerrada_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('do_evaluacion_acciones');
    }
};
