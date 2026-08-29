<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('class_level_id')->constrained();
            $table->foreignId('term_id')->constrained();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('total_marks');
            $table->integer('pass_marks');
            $table->date('exam_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};