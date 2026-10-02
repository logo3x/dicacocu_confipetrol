<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cada procedimiento corresponde a una categoría o cargo: dice a qué
     * personal aplica y, con ello, a quién hay que divulgárselo.
     */
    public function up(): void
    {
        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->string('categoria_cargo')->nullable()->after('nombre_actividad');
            $table->index('categoria_cargo');
        });
    }

    public function down(): void
    {
        Schema::table('do_procedimientos', function (Blueprint $table) {
            $table->dropIndex(['categoria_cargo']);
            $table->dropColumn('categoria_cargo');
        });
    }
};
