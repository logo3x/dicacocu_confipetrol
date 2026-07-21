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
        Schema::create('compromisos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('contrato')->nullable()->comment('null = compromiso corporativo/global, no ligado a un contrato específico');
            $table->date('fecha_limite');
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('rol_responsable')->nullable()->comment('rol de negocio esperado como responsable, ej: responsable_hseq, calidad_corporativa');
            $table->timestamp('cumplido_at')->nullable();
            $table->foreignId('cumplido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['contrato', 'fecha_limite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compromisos');
    }
};
