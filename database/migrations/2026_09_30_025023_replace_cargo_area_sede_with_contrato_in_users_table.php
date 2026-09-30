<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La información laboral del usuario pasa a ser su contrato y campo, que además
 * determinan qué procedimientos puede ver.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('contrato_id')->nullable()->after('name')->constrained('do_contratos')->nullOnDelete();
            $table->foreignId('campo_id')->nullable()->after('contrato_id')->constrained('do_campos')->nullOnDelete();
            $table->dropColumn(['cargo', 'area', 'sede']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contrato_id');
            $table->dropConstrainedForeignId('campo_id');
            $table->string('cargo')->nullable()->after('name');
            $table->string('area')->nullable()->after('cargo');
            $table->string('sede')->nullable()->after('area');
        });
    }
};
