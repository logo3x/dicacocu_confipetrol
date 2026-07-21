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
        Schema::create('actividad_personal_expuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha_socializacion')->nullable()->comment('null = pendiente de socializar');
            $table->date('fecha_vencimiento')->nullable()->comment('calculada: fecha_socializacion + 1 año');
            $table->foreignId('socializado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['actividad_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_personal_expuesto');
    }
};
