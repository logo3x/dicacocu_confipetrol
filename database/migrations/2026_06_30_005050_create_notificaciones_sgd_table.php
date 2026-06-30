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
        Schema::create('notificaciones_sgd', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tipo');
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('notificable_type')->nullable();
            $table->unsignedBigInteger('notificable_id')->nullable();
            $table->json('datos')->nullable();
            $table->timestamp('leido_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'leido_at']);
            $table->index(['notificable_type', 'notificable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_sgd');
    }
};
