<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_bus_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('bus_id')->constrained();
            $table->foreignId('route_id')->constrained();
            $table->string('pickup_point');
            $table->string('drop_off_point');
            $table->enum('status', ['assigned', 'active', 'completed', 'cancelled'])->default('assigned');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_bus_assignments');
    }
};