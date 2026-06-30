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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('codigo')->unique()->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo_documento')->default('procedimiento');
            $table->string('estado')->default('borrador');
            $table->foreignId('carpeta_id')->nullable()->constrained('carpetas')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('aprobador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('fase_dicacocu')->nullable()->comment('D-I-C-A-C-O-C-U');
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_revision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->integer('version_actual')->default(1);
            $table->json('tags')->nullable();
            $table->json('metadatos')->nullable();
            $table->boolean('requiere_firma')->default(false);
            $table->boolean('confidencial')->default(false);
            $table->unsignedBigInteger('visitas')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['estado', 'fase_dicacocu']);
            $table->index('carpeta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
