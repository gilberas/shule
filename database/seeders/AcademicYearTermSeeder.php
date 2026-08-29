<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Term;

class AcademicYearTermSeeder extends Seeder
{
    public function run(): void
    {
        // Seed academic years
        $academicYears = [
            ['name' => '2023-2024', 'start_date' => '2023-09-01', 'end_date' => '2024-06-30', 'is_current' => false],
            ['name' => '2024-2025', 'start_date' => '2024-09-01', 'end_date' => '2025-06-30', 'is_current' => true],
            ['name' => '2025-2026', 'start_date' => '2025-09-01', 'end_date' => '2026-06-30', 'is_current' => false],
        ];

        foreach ($academicYears as $year) {
            AcademicYear::create($year);
        }

        // Seed terms linked to academic years
        $terms = [
            ['academic_year_id' => 1, 'name' => 'First Term', 'start_date' => '2023-09-01', 'end_date' => '2023-12-20', 'is_current' => false],
            ['academic_year_id' => 1, 'name' => 'Second Term', 'start_date' => '2023-12-21', 'end_date' => '2024-06-30', 'is_current' => false],
            ['academic_year_id' => 2, 'name' => 'First Term', 'start_date' => '2024-09-01', 'end_date' => '2024-12-20', 'is_current' => true],
            ['academic_year_id' => 2, 'name' => 'Second Term', 'start_date' => '2024-12-21', 'end_date' => '2025-06-30', 'is_current' => false],
            ['academic_year_id' => 3, 'name' => 'First Term', 'start_date' => '2025-09-01', 'end_date' => '2025-12-20', 'is_current' => false],
            ['academic_year_id' => 3, 'name' => 'Second Term', 'start_date' => '2025-12-21', 'end_date' => '2026-06-30', 'is_current' => false],
        ];

        foreach ($terms as $term) {
            Term::create($term);
        }

        $this->command->info('Academic years and terms seeded successfully!');
    }
}