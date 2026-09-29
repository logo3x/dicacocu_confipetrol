<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('do_contratos', function (Blueprint $table) {
            $table->string('zona')->nullable()->after('nombre');
            $table->index('zona');
        });
    }

    public function down(): void
    {
        Schema::table('do_contratos', function (Blueprint $table) {
            $table->dropIndex(['zona']);
            $table->dropColumn('zona');
        });
    }
};
