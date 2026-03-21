<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('islamic_rulings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('ruling_topics')->cascadeOnDelete();
            $table->string('question', 500);
            $table->text('answer');
            $table->text('evidence')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['topic_id', 'is_active'], 'idx_rulings_topic');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('islamic_rulings');
    }
};
