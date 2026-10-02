<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * El comando de avisos comprueba a diario si ya notificó a alguien.
     * El índice que trae Laravel empieza por notifiable_type, que esa
     * consulta no usa, así que recorría la tabla entera. Y notifications es
     * la que más crece en este sistema.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(
                ['notifiable_id', 'type', 'created_at'],
                'notifications_destinatario_tipo_fecha_index',
            );
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_destinatario_tipo_fecha_index');
        });
    }
};
