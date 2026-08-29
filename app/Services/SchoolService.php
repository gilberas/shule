<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolParent;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Payment;
use App\Models\Grade;
use App\Models\Attendance;

class SchoolService
{
    /**
     * Get school info array for PDFs.
     */
    public static function getSchoolInfo(): array
    {
        return [
            'name' => config('school.name', 'Tanzania School Management System'),
            'short_name' => config('school.short_name', 'TSMS'),
            'motto' => config('school.motto', 'Knowledge for Excellence'),
            'address' => config('school.address', 'P.O. Box 1234, Dar es Salaam, Tanzania'),
            'phone' => config('school.phone', '+255 22 123 4567'),
            'email' => config('school.email', 'info@tsms.ac.tz'),
            'website' => config('school.website', 'www.tsms.ac.tz'),
            'registration_number' => config('school.registration_number', 'SCH-2024-001'),
            'currency' => config('school.currency', 'TZS'),
            'currency_symbol' => config('school.currency_symbol', 'TSh'),
        ];
    }

    /**
     * Format currency amount.
     */
    public static function formatCurrency(float $amount): string
    {
        return number_format($amount, 0, '.', ',') . ' ' . config('school.currency_symbol', 'TSh');
    }

    /**
     * Get current term info.
     */
    public static function getCurrentTerm(): ?array
    {
        $term = \App\Models\Term::with('academicYear')->where('is_current', true)->first();
        if (!$term) return null;

        return [
            'id' => $term->id,
            'name' => $term->name,
            'academic_year' => $term->academicYear->name ?? 'N/A',
            'start_date' => $term->start_date,
            'end_date' => $term->end_date,
        ];
    }
}
