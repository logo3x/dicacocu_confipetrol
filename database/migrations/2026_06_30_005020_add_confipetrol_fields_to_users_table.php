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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cargo')->nullable()->after('name');
            $table->string('area')->nullable()->after('cargo');
            $table->string('sede')->nullable()->after('area');
            $table->boolean('is_active')->default(true)->after('sede');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('avatar_url')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cargo', 'area', 'sede', 'is_active', 'last_login_at', 'avatar_url']);
        });
    }
};
