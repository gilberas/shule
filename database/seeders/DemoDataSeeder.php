<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\FeeStructure;
use App\Models\Grade;
use App\Models\SchoolParent;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $currentYear = AcademicYear::where('is_current', true)->first();
        $currentTerm = $currentYear ? Term::where('academic_year_id', $currentYear->id)->where('is_current', true)->first() : null;

        $grade1 = ClassLevel::where('name', 'Grade 1')->first();
        $grade2 = ClassLevel::where('name', 'Grade 2')->first();
        $grade3 = ClassLevel::where('name', 'Grade 3')->first();
        $form1 = ClassLevel::where('name', 'Form 1')->first();

        $this->ensureStreams($grade1);
        $this->ensureStreams($grade2);
        $this->ensureStreams($grade3);
        $this->ensureStreams($form1);

        $math = Subject::where('name', 'Mathematics')->first();
        $english = Subject::where('name', 'English')->first();

        $this->createTeachers();
        $this->createStudents($grade1, $grade2, $grade3, $form1, $currentYear);
        $this->createParents();
        $this->createAttendance($currentTerm, $grade1, $math);
        $this->createExams($currentTerm, $grade1, $math, $english);
        $this->createGrades($currentTerm, $grade1, $math, $english);
        $this->createFeeStructures($grade1, $currentTerm);
        $this->createPayments($currentTerm);
        $this->createDemoUsers();

        $this->command->info('Demo data seeded successfully!');
    }

    private function ensureStreams(ClassLevel $level): void
    {
        if (Stream::where('class_level_id', $level->id)->exists()) return;

        foreach (['A', 'B', 'C', 'D'] as $name) {
            Stream::create([
                'name' => $name,
                'class_level_id' => $level->id,
                'capacity' => 40,
            ]);
        }
    }

    private function createTeachers(): void
    {
        $teacherData = [
            ['first_name' => 'John', 'last_name' => 'Mwalimu', 'subject' => 'Mathematics'],
            ['first_name' => 'Sarah', 'last_name' => 'Kimaro', 'subject' => 'English'],
            ['first_name' => 'David', 'last_name' => 'Nyerere', 'subject' => 'Physics'],
            ['first_name' => 'Grace', 'last_name' => 'Mushi', 'subject' => 'Chemistry'],
            ['first_name' => 'Peter', 'last_name' => 'Ochieng', 'subject' => 'Biology'],
            ['first_name' => 'Mary', 'last_name' => 'Juma', 'subject' => 'History'],
            ['first_name' => 'James', 'last_name' => 'Kamau', 'subject' => 'Geography'],
            ['first_name' => 'Anne', 'last_name' => 'Baraka', 'subject' => 'Computer Science'],
            ['first_name' => 'Joseph', 'last_name' => 'Msuya', 'subject' => 'Mathematics'],
            ['first_name' => 'Elizabeth', 'last_name' => 'Mwangwa', 'subject' => 'English'],
            ['first_name' => 'Robert', 'last_name' => 'Lugalo', 'subject' => 'Physics'],
            ['first_name' => 'Catherine', 'last_name' => 'Rweyemamu', 'subject' => 'Biology'],
            ['first_name' => 'Michael', 'last_name' => 'Kibona', 'subject' => 'Chemistry'],
            ['first_name' => 'Helen', 'last_name' => 'Mwakasege', 'subject' => 'History'],
            ['first_name' => 'Daniel', 'last_name' => 'Sengo', 'subject' => 'Geography'],
        ];

        foreach ($teacherData as $i => $data) {
            $subject = Subject::where('name', $data['subject'])->first();
            if (!$subject) continue;
            Teacher::updateOrCreate(
                ['email' => strtolower($data['first_name']) . '.' . strtolower($data['last_name']) . '@tsms.test'],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => '+2557' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                    'subject_id' => $subject->id,
                    'hire_date' => '2024-01-15',
                    'status' => 'active',
                ]
            );
        }
    }

    private function createStudents(ClassLevel $grade1, ClassLevel $grade2, ClassLevel $grade3, ClassLevel $form1, ?AcademicYear $year): void
    {
        $firstNames = ['Amina', 'Baraka', 'Cecilia', 'Daniel', 'Esther', 'Furaha', 'George', 'Happiness', 'Ibrahim', 'Joyce',
            'Kevin', 'Lilian', 'Moses', 'Nancy', 'Oscar', 'Patricia', 'Rehema', 'Samuel', 'Teresa', 'Ulrich',
            'Violet', 'William', 'Xena', 'Yusuf', 'Zainab', 'Adam', 'Beatrice', 'Charles', 'Diana', 'Emmanuel',
            'Flora', 'Godfrey', 'Hawa', 'Isaac', 'Janet', 'Kelvin', 'Linda', 'Michael', 'Neema', 'Oliver',
            'Priscilla', 'Richard', 'Sylvia', 'Thomas', 'Urania', 'Victor', 'Winnie', 'Xavier', 'Yvette', 'Zephania'];
        $lastNames = ['Mwangi', 'Ochieng', 'Kimaro', 'Nyerere', 'Mushi', 'Juma', 'Kamau', 'Baraka', 'Msuya', 'Mwangwa',
            'Lugalo', 'Rweyemamu', 'Kibona', 'Mwakasege', 'Sengo', 'Mwalimu', 'Temu', 'Sharma', 'Patel', 'Singh',
            'Omondi', 'Wekesa', 'Mutua', 'Kioko', 'Macharia', 'Njoroge', 'Kariuki', 'Wambua', 'Maunda', 'Opiyo'];

        $studentIndex = 0;
        $classLevels = [
            ['level' => $grade1, 'count' => 40],
            ['level' => $grade2, 'count' => 40],
            ['level' => $grade3, 'count' => 40],
            ['level' => $form1, 'count' => 30],
        ];

        $faker = \Faker\Factory::create();

        foreach ($classLevels as $cl) {
            $levelStreams = Stream::where('class_level_id', $cl['level']->id)->get();
            if ($levelStreams->isEmpty()) continue;

            for ($i = 0; $i < $cl['count']; $i++) {
                $stream = $levelStreams[$i % $levelStreams->count()];
                $fName = $firstNames[$studentIndex % count($firstNames)];
                $lName = $lastNames[$studentIndex % count($lastNames)];
                $studentIndex++;

                Student::updateOrCreate(
                    ['email' => strtolower($fName) . '.' . strtolower($lName) . $studentIndex . '@student.tsms.test'],
                    [
                        'first_name' => $fName,
                        'last_name' => $lName,
                        'class_level_id' => $cl['level']->id,
                        'stream_id' => $stream->id,
                        'academic_year_id' => $year->id,
                        'date_of_birth' => $faker->date('Y-m-d', '2012-01-01'),
                        'enrollment_date' => '2024-09-01',
                        'status' => 'active',
                    ]
                );
            }
        }
    }

    private function createParents(): void
    {
        $parentData = [
            ['first_name' => 'James', 'last_name' => 'Mwangi', 'email' => 'james.mwangi@tsms.test', 'phone' => '+255710000001'],
            ['first_name' => 'Fatuma', 'last_name' => 'Ochieng', 'email' => 'fatuma.ochieng@tsms.test', 'phone' => '+255710000002'],
            ['first_name' => 'Hassan', 'last_name' => 'Kimaro', 'email' => 'hassan.kimaro@tsms.test', 'phone' => '+255710000003'],
            ['first_name' => 'Agnes', 'last_name' => 'Nyerere', 'email' => 'agnes.nyerere@tsms.test', 'phone' => '+255710000004'],
            ['first_name' => 'Emmanuel', 'last_name' => 'Mushi', 'email' => 'emmanuel.mushi@tsms.test', 'phone' => '+255710000005'],
        ];

        foreach ($parentData as $pData) {
            SchoolParent::updateOrCreate(
                ['email' => $pData['email']],
                [
                    'first_name' => $pData['first_name'],
                    'last_name' => $pData['last_name'],
                    'phone' => $pData['phone'],
                ]
            );
        }

        $students = Student::take(10)->get();
        $parents = SchoolParent::all();
        if ($parents->isEmpty()) return;

        foreach ($students as $i => $student) {
            $parent = $parents[$i % $parents->count()];
            $student->update([
                'parent_id' => $parent->id,
                'parent_name' => $parent->first_name . ' ' . $parent->last_name,
                'parent_contact' => $parent->phone,
            ]);
        }
    }

    private function createAttendance(?Term $currentTerm, ClassLevel $grade1, Subject $math): void
    {
        if (!$currentTerm) return;

        $students = Student::where('class_level_id', $grade1->id)->take(20)->get();
        $dates = $this->generateSchoolDays($currentTerm->start_date, 30);
        $faker = \Faker\Factory::create();

        $records = [];
        $now = now()->format('Y-m-d H:i:s');

        foreach ($dates as $date) {
            foreach ($students as $student) {
                $records[] = [
                    'student_id' => $student->id,
                    'subject_id' => $math->id,
                    'class_level_id' => $grade1->id,
                    'term_id' => $currentTerm->id,
                    'date' => $date,
                    'status' => $faker->randomElement(['present', 'present', 'present', 'present', 'absent', 'excused']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('attendance')->insert($records);
    }

    private function createExams(?Term $currentTerm, ClassLevel $grade1, Subject $math, Subject $english): void
    {
        if (!$currentTerm) return;

        $exams = [
            ['name' => 'Mid-Term Exam', 'subject' => $math, 'total_marks' => 100, 'pass_marks' => 50, 'date' => $currentTerm->start_date],
            ['name' => 'End-Term Exam', 'subject' => $math, 'total_marks' => 100, 'pass_marks' => 50, 'date' => $currentTerm->end_date],
            ['name' => 'Mid-Term Exam', 'subject' => $english, 'total_marks' => 100, 'pass_marks' => 50, 'date' => $currentTerm->start_date],
            ['name' => 'End-Term Exam', 'subject' => $english, 'total_marks' => 100, 'pass_marks' => 50, 'date' => $currentTerm->end_date],
        ];

        foreach ($exams as $exam) {
            Exam::updateOrCreate(
                [
                    'name' => $exam['name'],
                    'subject_id' => $exam['subject']->id,
                    'class_level_id' => $grade1->id,
                    'term_id' => $currentTerm->id,
                ],
                [
                    'description' => $exam['name'] . ' for Grade 1',
                    'total_marks' => $exam['total_marks'],
                    'pass_marks' => $exam['pass_marks'],
                    'exam_date' => $exam['date'],
                ]
            );
        }
    }

    private function createGrades(?Term $currentTerm, ClassLevel $grade1, Subject $math, Subject $english): void
    {
        if (!$currentTerm) return;

        $students = Student::where('class_level_id', $grade1->id)->take(20)->get();
        $faker = \Faker\Factory::create();
        $now = now()->format('Y-m-d H:i:s');

        $records = [];
        foreach ($students as $student) {
            foreach ([$math, $english] as $subject) {
                foreach (['Mid-Term Exam', 'End-Term Exam'] as $examType) {
                    $score = $faker->numberBetween(25, 98);
                    $records[] = [
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'class_level_id' => $grade1->id,
                        'term_id' => $currentTerm->id,
                        'exam_type' => $examType,
                        'score' => $score,
                        'grade_letter' => $this->scoreToGrade($score),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        DB::table('grades')->insert($records);
    }

    private function createFeeStructures(ClassLevel $grade1, ?Term $currentTerm): void
    {
        $fees = [
            ['name' => 'Tuition Fee', 'amount' => 250000, 'frequency' => 'term'],
            ['name' => 'Development Fee', 'amount' => 50000, 'frequency' => 'term'],
            ['name' => 'Examination Fee', 'amount' => 30000, 'frequency' => 'term'],
            ['name' => 'Library Fee', 'amount' => 15000, 'frequency' => 'term'],
            ['name' => 'Sports Fee', 'amount' => 20000, 'frequency' => 'term'],
        ];

        foreach ($fees as $fee) {
            FeeStructure::updateOrCreate(
                ['name' => $fee['name']],
                [
                    'amount' => $fee['amount'],
                    'frequency' => $fee['frequency'],
                    'class_level_id' => $grade1->id,
                    'term_id' => $currentTerm?->id,
                    'is_active' => true,
                ]
            );
        }
    }

    private function createPayments(?Term $currentTerm): void
    {
        if (!$currentTerm) return;

        $students = Student::take(15)->get();
        $fees = FeeStructure::where('term_id', $currentTerm->id)->get();
        if ($fees->isEmpty()) return;

        $faker = \Faker\Factory::create();
        $statuses = ['paid', 'paid', 'paid', 'partial', 'partial', 'pending'];

        foreach ($students as $i => $student) {
            $fee = $fees[$i % $fees->count()];
            $status = $statuses[$i % count($statuses)];
            $amount = match ($status) {
                'paid' => $fee->amount,
                'partial' => $fee->amount * 0.5,
                default => 0,
            };

            Payment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'fee_structure_id' => $fee->id,
                ],
                [
                    'amount' => $amount,
                    'payment_date' => now()->subDays($faker->numberBetween(1, 30)),
                    'payment_method' => $faker->randomElement(['cash', 'bank_transfer', 'card', 'online']),
                    'status' => $status,
                    'receipt_number' => $status !== 'pending' ? 'REC-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT) : null,
                ]
            );
        }
    }

    private function createDemoUsers(): void
    {
        $demoUsers = [
            ['name' => 'Super Admin', 'email' => 'superadmin@tsms.test', 'role' => 'super_admin', 'phone' => '+255700000001'],
            ['name' => 'School Admin', 'email' => 'admin@tsms.test', 'role' => 'admin', 'phone' => '+255700000002'],
            ['name' => 'Mr. John Mwalimu', 'email' => 'teacher@tsms.test', 'role' => 'teacher', 'phone' => '+255700000003'],
            ['name' => 'Ms. Jane Accountant', 'email' => 'accountant@tsms.test', 'role' => 'accountant', 'phone' => '+255700000004'],
            ['name' => 'Mr. James Parent', 'email' => 'parent@tsms.test', 'role' => 'parent', 'phone' => '+255700000005'],
            ['name' => 'Amina Student', 'email' => 'student@tsms.test', 'role' => 'student', 'phone' => '+255700000006'],
        ];

        foreach ($demoUsers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => $data['role'],
                    'phone' => $data['phone'],
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles($data['role']);
        }
    }

    private function scoreToGrade(float $score): string
    {
        return match(true) {
            $score >= 80 => 'A',
            $score >= 70 => 'B',
            $score >= 60 => 'C',
            $score >= 50 => 'D',
            $score >= 40 => 'E',
            default => 'F',
        };
    }

    private function generateSchoolDays(string $startDate, int $days): array
    {
        $dates = [];
        $date = new \DateTime($startDate);
        $count = 0;

        while ($count < $days) {
            if (!in_array((int) $date->format('N'), [6, 7])) {
                $dates[] = $date->format('Y-m-d');
                $count++;
            }
            $date->modify('+1 day');
        }

        return $dates;
    }
}
