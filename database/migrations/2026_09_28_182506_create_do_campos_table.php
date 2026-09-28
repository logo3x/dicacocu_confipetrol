<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo de campos operativos, cada uno perteneciente a un contrato.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('do_campos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('do_contratos')->cascadeOnDelete();
            $table->string('codigo')->nullable();
            $table->string('nombre');
            $table->string('ubicacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['contrato_id', 'nombre']);
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('do_campos');
    }
};
