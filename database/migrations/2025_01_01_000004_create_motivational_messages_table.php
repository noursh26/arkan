<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motivational_messages', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->enum('prayer_time', ['any', 'fajr', 'dhuhr', 'asr', 'maghrib', 'isha'])->default('any');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['prayer_time', 'is_active'], 'idx_messages_prayer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motivational_messages');
    }
};
