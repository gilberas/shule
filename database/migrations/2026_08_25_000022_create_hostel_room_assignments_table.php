<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_room_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained();
            $table->string('room_number');
            $table->integer('capacity');
            $table->integer('current_occupants')->default(0);
            $table->integer('floor');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_room_assignments');
    }
};