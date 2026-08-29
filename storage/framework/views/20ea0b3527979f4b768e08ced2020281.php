<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($pageTitle ?? 'TSMS'); ?> — Tanzania School Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..900&family=Inter:wght@300..700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-background text-foreground" x-data="{ sidebarOpen: false }">
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-chalkboard/50 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

    <?php
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
    ?>

    <?php echo $__env->make('layouts._sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="lg:pl-64">
        <?php echo $__env->make('layouts._topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="p-4 sm:p-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($pageTitle)): ?>
                <div class="mb-6">
                    <h1 class="font-display text-2xl font-semibold text-foreground"><?php echo e($pageTitle); ?></h1>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($pageSubtitle)): ?>
                        <p class="mt-1 text-sm text-muted-foreground"><?php echo e($pageSubtitle); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\user\shule\resources\views/layouts/app.blade.php ENDPATH**/ ?>