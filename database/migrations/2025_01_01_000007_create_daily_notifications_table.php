<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('body');
            $table->enum('type', ['khulq', 'nafl', 'dua', 'reminder'])->default('reminder');
            $table->date('scheduled_date')->nullable();
            $table->time('send_time')->default('07:00:00');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheduled_date', 'is_sent'], 'idx_notifs_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_notifications');
    }
};
