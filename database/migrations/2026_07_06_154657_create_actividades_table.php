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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('contrato');
            $table->string('campo')->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('personal_expuesto')->default(0);
            $table->unsignedTinyInteger('valoracion_amenaza')->nullable()->comment('0-100. 80-100=bajo, 60-79=medio, 0-59=alto');
            $table->string('prioridad_amenaza')->nullable()->comment('bajo|medio|alto, derivado de valoracion_amenaza');
            $table->date('fecha_identificacion')->nullable();
            $table->date('fecha_limite_estandarizacion')->nullable()->comment('calculada: bajo=+4 meses, medio=+2 meses, alto=+1 mes desde fecha_identificacion');
            $table->date('fecha_limite_verificacion')->nullable()->comment('calculada: bajo=+12 meses, medio=+6 meses, alto=+3 meses desde fecha_estandarizacion');
            $table->foreignId('documento_id')->nullable()->constrained('documentos')->nullOnDelete();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['contrato', 'prioridad_amenaza']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
