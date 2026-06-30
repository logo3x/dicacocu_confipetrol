<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lectura_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('leido_at')->nullable();
            $table->boolean('confirmado')->default(false);
            $table->timestamp('confirmado_at')->nullable();
            $table->unsignedSmallInteger('progreso_pct')->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->unique(['documento_id', 'user_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lectura_documentos');
    }
};
