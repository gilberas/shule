<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $allPermissions = [
            'manage-users', 'manage-roles', 'manage-school', 'manage-academic',
            'manage-teachers', 'manage-students', 'manage-parents', 'manage-fees',
            'manage-grades', 'manage-attendance', 'manage-timetable', 'manage-exams',
            'manage-library', 'manage-transportation', 'manage-hostel', 'manage-messages',
            'view-reports', 'manage-settings', 'view-students',
            'view-child-grades', 'view-child-attendance', 'make-payments',
            'view-own-grades', 'view-own-attendance', 'view-timetable', 'view-messages',
        ];

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        $rolePermissions = [
            'super_admin' => [
                'manage-users', 'manage-roles', 'manage-school', 'manage-academic',
                'manage-teachers', 'manage-students', 'manage-parents', 'manage-fees',
                'manage-grades', 'manage-attendance', 'manage-timetable', 'manage-exams',
                'manage-library', 'manage-transportation', 'manage-hostel', 'manage-messages',
                'view-reports', 'manage-settings',
            ],
            'admin' => [
                'manage-academic', 'manage-teachers', 'manage-students', 'manage-parents',
                'manage-fees', 'manage-grades', 'manage-attendance', 'manage-timetable',
                'manage-exams', 'manage-library', 'manage-transportation', 'manage-hostel',
                'manage-messages', 'view-reports',
            ],
            'teacher' => [
                'manage-grades', 'manage-attendance', 'manage-timetable', 'manage-exams',
                'manage-messages', 'view-students',
            ],
            'accountant' => [
                'manage-fees', 'view-reports',
            ],
            'parent' => [
                'view-child-grades', 'view-child-attendance', 'manage-messages', 'make-payments',
            ],
            'student' => [
                'view-own-grades', 'view-own-attendance', 'view-timetable', 'view-messages',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@tsms.com',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'phone' => '+255700000001',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'School Admin',
                'email' => 'admin@tsms.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '+255700000002',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mr. John Teacher',
                'email' => 'teacher@tsms.com',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'phone' => '+255700000003',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ms. Jane Accountant',
                'email' => 'accountant@tsms.com',
                'password' => bcrypt('password'),
                'role' => 'accountant',
                'phone' => '+255700000004',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mr. James Parent',
                'email' => 'parent@tsms.com',
                'password' => bcrypt('password'),
                'role' => 'parent',
                'phone' => '+255700000005',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Amina Student',
                'email' => 'student@tsms.com',
                'password' => bcrypt('password'),
                'role' => 'student',
                'phone' => '+255700000006',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole($userData['role']);
        }
    }
}
