<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['term', 'semester', 'annual', 'one_time'])->default('term');
            $table->foreignId('education_level_id')->nullable()->constrained();
            $table->foreignId('class_level_id')->nullable()->constrained();
            $table->foreignId('subject_id')->nullable()->constrained();
            $table->foreignId('term_id')->nullable()->constrained();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
