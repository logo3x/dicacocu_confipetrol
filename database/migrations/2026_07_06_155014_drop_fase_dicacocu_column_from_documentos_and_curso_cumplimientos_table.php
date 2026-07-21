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
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropIndex('idx_documentos_fase_estado');
            $table->dropIndex(['estado', 'fase_dicacocu']);
            $table->dropColumn('fase_dicacocu');
        });

        Schema::table('curso_cumplimientos', function (Blueprint $table) {
            $table->dropColumn('fase_dicacocu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('fase_dicacocu')->nullable()->comment('D-I-C-A-C-O-C-U');
            $table->index(['estado', 'fase_dicacocu']);
            $table->index(['fase_dicacocu', 'estado'], 'idx_documentos_fase_estado');
        });

        Schema::table('curso_cumplimientos', function (Blueprint $table) {
            $table->string('fase_dicacocu', 5)->nullable();
        });
    }
};
