<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationLevel;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\Subject;

class Phase1DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $educationLevels = [
            ['name' => 'Primary', 'education_level' => 'primary', 'order' => 1],
            ['name' => 'Secondary', 'education_level' => 'o-level', 'order' => 2],
            ['name' => 'High School', 'education_level' => 'a-level', 'order' => 3],
            ['name' => 'University', 'education_level' => 'primary', 'order' => 4],
        ];

        foreach ($educationLevels as $level) {
            EducationLevel::create($level);
        }

        $classLevels = [
            ['name' => 'Grade 1', 'display_order' => 1, 'education_level_id' => 1, 'capacity' => 40],
            ['name' => 'Grade 2', 'display_order' => 2, 'education_level_id' => 1, 'capacity' => 40],
            ['name' => 'Grade 3', 'display_order' => 3, 'education_level_id' => 1, 'capacity' => 40],
            ['name' => 'Grade 4', 'display_order' => 4, 'education_level_id' => 1, 'capacity' => 40],
            ['name' => 'Grade 5', 'display_order' => 5, 'education_level_id' => 1, 'capacity' => 40],
            ['name' => 'Grade 6', 'display_order' => 6, 'education_level_id' => 1, 'capacity' => 40],
            ['name' => 'Form 1', 'display_order' => 7, 'education_level_id' => 2, 'capacity' => 40],
            ['name' => 'Form 2', 'display_order' => 8, 'education_level_id' => 2, 'capacity' => 40],
            ['name' => 'Form 3', 'display_order' => 9, 'education_level_id' => 2, 'capacity' => 40],
            ['name' => 'Form 4', 'display_order' => 10, 'education_level_id' => 2, 'capacity' => 40],
        ];

        foreach ($classLevels as $level) {
            ClassLevel::create($level);
        }

        $streams = [
            ['name' => 'A', 'class_level_id' => 1, 'capacity' => 40],
            ['name' => 'B', 'class_level_id' => 1, 'capacity' => 40],
            ['name' => 'C', 'class_level_id' => 1, 'capacity' => 40],
            ['name' => 'D', 'class_level_id' => 1, 'capacity' => 40],
        ];

        foreach ($streams as $stream) {
            Stream::create($stream);
        }

        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MTH101', 'is_compulsory' => true, 'education_level_id' => 1],
            ['name' => 'Physics', 'code' => 'PHY101', 'is_compulsory' => true, 'education_level_id' => 1],
            ['name' => 'Chemistry', 'code' => 'CHM101', 'is_compulsory' => true, 'education_level_id' => 1],
            ['name' => 'Biology', 'code' => 'BIO101', 'is_compulsory' => true, 'education_level_id' => 1],
            ['name' => 'English', 'code' => 'ENG101', 'is_compulsory' => true, 'education_level_id' => 1],
            ['name' => 'History', 'code' => 'HIS101', 'is_compulsory' => false, 'education_level_id' => 2],
            ['name' => 'Geography', 'code' => 'GEO101', 'is_compulsory' => false, 'education_level_id' => 2],
            ['name' => 'Computer Science', 'code' => 'CS101', 'is_compulsory' => false, 'education_level_id' => 2],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Phase 1 database seeded successfully!');
    }
}
