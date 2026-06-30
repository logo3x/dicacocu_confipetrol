<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_cumplimientos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('fase_dicacocu', 5)->nullable();
            $table->string('estado')->default('activo');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('documentos_ids')->nullable();
            $table->json('preguntas')->nullable();
            $table->unsignedSmallInteger('nota_aprobacion')->default(70);
            $table->date('fecha_limite')->nullable();
            $table->boolean('certificado_activo')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('curso_inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('curso_cumplimientos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('estado')->default('pendiente');
            $table->unsignedSmallInteger('nota')->nullable();
            $table->json('respuestas')->nullable();
            $table->timestamp('completado_at')->nullable();
            $table->timestamp('certificado_at')->nullable();
            $table->timestamps();

            $table->unique(['curso_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_inscripciones');
        Schema::dropIfExists('curso_cumplimientos');
    }
};
