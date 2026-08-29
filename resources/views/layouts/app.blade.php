<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'TSMS' }} — Tanzania School Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..900&family=Inter:wght@300..700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-background text-foreground" x-data="{ sidebarOpen: false }">
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-chalkboard/50 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

    @php
        $user = auth()->user();
        $role = $user->role ?? 'admin';
        $currentRoute = request()->route()?->getName() ?? '';

        $navItems = match($role) {
            'admin', 'super_admin' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home'],
                ['group' => 'Academics', 'items' => [
                    ['label' => 'Students', 'route' => 'admin.students', 'icon' => 'users'],
                    ['label' => 'Teachers', 'route' => 'admin.teachers', 'icon' => 'academic'],
                    ['label' => 'Grades', 'route' => 'admin.grades', 'icon' => 'clipboard'],
                    ['label' => 'Attendance', 'route' => 'admin.attendance', 'icon' => 'check-circle'],
                    ['label' => 'Exams', 'route' => 'admin.exams', 'icon' => 'document'],
                    ['label' => 'Timetable', 'route' => 'admin.timetable', 'icon' => 'calendar'],
                ]],
                ['group' => 'Finance', 'items' => [
                    ['label' => 'Fees & Payments', 'route' => 'admin.fees', 'icon' => 'currency'],
                ]],
                ['group' => 'Community', 'items' => [
                    ['label' => 'Parents', 'route' => 'admin.parents', 'icon' => 'people'],
                    ['label' => 'Messages', 'route' => 'admin.messages', 'icon' => 'mail'],
                ]],
                ['group' => 'Reports', 'items' => [
                    ['label' => 'Reports & Documents', 'route' => 'admin.reports', 'icon' => 'document'],
                ]],
                ['group' => 'Administration', 'items' => [
                    ['label' => 'Academic Years', 'route' => 'admin.academic.years-terms', 'icon' => 'academic'],
                ]],
            ],
            'teacher' => [
                ['label' => 'Dashboard', 'route' => 'teacher.dashboard', 'icon' => 'home'],
                ['label' => 'My Attendance', 'route' => 'teacher.attendance', 'icon' => 'check-circle'],
                ['label' => 'My Marks', 'route' => 'teacher.marks', 'icon' => 'clipboard'],
                ['label' => 'My Timetable', 'route' => 'teacher.timetable', 'icon' => 'calendar'],
                ['label' => 'My Students', 'route' => 'teacher.students', 'icon' => 'users'],
            ],
            'accountant' => [
                ['label' => 'Dashboard', 'route' => 'accountant.dashboard', 'icon' => 'home'],
                ['label' => 'Fees & Payments', 'route' => 'admin.fees', 'icon' => 'currency'],
                ['label' => 'Fee Structures', 'route' => 'admin.fees.structures', 'icon' => 'document'],
                ['label' => 'Invoices', 'route' => 'admin.fees.invoices', 'icon' => 'clipboard'],
            ],
            'parent' => [
                ['label' => 'Dashboard', 'route' => 'parent.dashboard', 'icon' => 'home'],
                ['label' => 'My Children', 'route' => 'parent.children', 'icon' => 'people'],
                ['label' => 'Fee Payments', 'route' => 'parent.fees', 'icon' => 'currency'],
            ],
            'student' => [
                ['label' => 'Dashboard', 'route' => 'student.dashboard', 'icon' => 'home'],
                ['label' => 'My Grades', 'route' => 'student.grades', 'icon' => 'clipboard'],
                ['label' => 'My Attendance', 'route' => 'student.attendance', 'icon' => 'check-circle'],
                ['label' => 'My Timetable', 'route' => 'student.timetable', 'icon' => 'calendar'],
            ],
            default => [],
        };
    @endphp

    @include('layouts._sidebar')

    <div class="lg:pl-64">
        @include('layouts._topbar')

        <main class="p-4 sm:p-6">
            @if(isset($pageTitle))
                <div class="mb-6">
                    <h1 class="font-display text-2xl font-semibold text-foreground">{{ $pageTitle }}</h1>
                    @if(isset($pageSubtitle))
                        <p class="mt-1 text-sm text-muted-foreground">{{ $pageSubtitle }}</p>
                    @endif
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
