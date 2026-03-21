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
        Schema::table('app_devices', function (Blueprint $table) {
            $table->renameColumn('fcm_token', 'player_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_devices', function (Blueprint $table) {
            $table->renameColumn('player_id', 'fcm_token');
        });
    }
};
