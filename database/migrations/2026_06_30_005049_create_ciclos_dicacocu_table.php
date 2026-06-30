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
        Schema::create('ciclos_dicacocu', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->string('fase')->comment('D=Disponibilidad, I=Integridad, C=Calidad, A=Acceso, C=Comunicacion, O=Operacion, C=Cumplimiento, U=Uso');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->string('estado')->default('activo');
            $table->foreignId('responsable_id')->constrained('users')->cascadeOnDelete();
            $table->json('documentos_ids')->nullable();
            $table->integer('progreso')->default(0)->comment('0-100');
            $table->json('indicadores')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['fase', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos_dicacocu');
    }
};
