<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adhkar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('adhkar_categories')->cascadeOnDelete();
            $table->text('text');
            $table->string('source', 255)->nullable();
            $table->tinyInteger('count')->default(1);
            $table->smallInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active'], 'idx_adhkar_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adhkar');
    }
};
