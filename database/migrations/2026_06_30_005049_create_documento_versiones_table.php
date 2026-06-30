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
        Schema::create('documento_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos')->cascadeOnDelete();
            $table->integer('version');
            $table->text('cambios')->nullable();
            $table->string('estado')->default('borrador');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('revisado_at')->nullable();
            $table->timestamp('aprobado_at')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->string('archivo_path')->nullable();
            $table->string('archivo_nombre')->nullable();
            $table->unsignedBigInteger('archivo_size')->nullable();
            $table->string('archivo_mime')->nullable();
            $table->text('contenido_ocr')->nullable();
            $table->timestamps();

            $table->unique(['documento_id', 'version']);
            $table->index('documento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_versiones');
    }
};
