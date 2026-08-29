<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('admission_number')->nullable()->after('id');
        });

        // Generate admission numbers for existing students
        $students = DB::table('students')->orderBy('id')->get();
        foreach ($students as $index => $student) {
            $year = $student->enrollment_date ? date('Y', strtotime($student->enrollment_date)) : date('Y');
            $number = str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            DB::table('students')
                ->where('id', $student->id)
                ->update(['admission_number' => "TSMS-{$year}-{$number}"]);
        }
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('admission_number');
        });
    }
};
