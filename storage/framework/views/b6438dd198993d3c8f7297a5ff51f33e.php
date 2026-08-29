<?php
    $pageTitle = 'Reports & Documents';
    $pageSubtitle = 'Generate PDF and Excel reports';
?>



<?php $__env->startSection('content'); ?>
<div x-data="reportGenerator()">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        
        <div class="rounded-xl border border-border bg-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-emerald-500/10">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.671 1.171 1.671.879-.659m-3.22 0 .879-.659 1.171 1.671 1.171-1.671.879.659m-3.22 0 .879-.659L15 12.372l-1.171 1.671-.879-.659M12 3v3"/></svg>
                </div>
                <div>
                    <h3 class="font-display text-lg font-semibold">Financial Reports</h3>
                    <p class="text-xs text-muted-foreground">Payments, invoices, receipts</p>
                </div>
            </div>
            <div class="space-y-2">
                <button @click="openModal('receipt')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-emerald-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Payment Receipt</span>
                        <span class="text-xs text-muted-foreground">Generate receipt for a payment</span>
                    </div>
                </button>
                <button @click="openModal('invoice')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-blue-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Fee Invoice</span>
                        <span class="text-xs text-muted-foreground">Generate invoice for a student</span>
                    </div>
                </button>
                <button @click="openModal('fee-statement')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-purple-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Fee Statement</span>
                        <span class="text-xs text-muted-foreground">Complete transaction history</span>
                    </div>
                </button>
                <a href="<?php echo e(route('admin.reports.export.payments')); ?>" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-orange-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg></span>
                    <div>
                        <span class="font-medium block">Payment History (Excel)</span>
                        <span class="text-xs text-muted-foreground">Export all payments to Excel</span>
                    </div>
                </a>
            </div>
        </div>

        
        <div class="rounded-xl border border-border bg-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-blue-500/10">
                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                </div>
                <div>
                    <h3 class="font-display text-lg font-semibold">Academic Reports</h3>
                    <p class="text-xs text-muted-foreground">Report cards, class performance</p>
                </div>
            </div>
            <div class="space-y-2">
                <button @click="openModal('report-card')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-blue-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Report Card</span>
                        <span class="text-xs text-muted-foreground">Student term report card</span>
                    </div>
                </button>
                <button @click="openModal('class-performance')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-indigo-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Class Performance</span>
                        <span class="text-xs text-muted-foreground">Class averages and rankings</span>
                    </div>
                </button>
                <a href="<?php echo e(route('admin.reports.export.grades')); ?>" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-green-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg></span>
                    <div>
                        <span class="font-medium block">Grades (Excel)</span>
                        <span class="text-xs text-muted-foreground">Export all grades to Excel</span>
                    </div>
                </a>
            </div>
        </div>

        
        <div class="rounded-xl border border-border bg-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-amber-500/10">
                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <h3 class="font-display text-lg font-semibold">Attendance Reports</h3>
                    <p class="text-xs text-muted-foreground">Student and class attendance</p>
                </div>
            </div>
            <div class="space-y-2">
                <button @click="openModal('attendance-student')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-amber-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Student Attendance</span>
                        <span class="text-xs text-muted-foreground">Individual student report</span>
                    </div>
                </button>
                <button @click="openModal('attendance-class')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-teal-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Class Attendance</span>
                        <span class="text-xs text-muted-foreground">Class-wide attendance report</span>
                    </div>
                </button>
                <a href="<?php echo e(route('admin.reports.export.attendance')); ?>" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-red-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg></span>
                    <div>
                        <span class="font-medium block">Attendance (Excel)</span>
                        <span class="text-xs text-muted-foreground">Export attendance to Excel</span>
                    </div>
                </a>
            </div>
        </div>

        
        <div class="rounded-xl border border-border bg-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-violet-500/10">
                    <svg class="h-5 w-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                </div>
                <div>
                    <h3 class="font-display text-lg font-semibold">Administrative Reports</h3>
                    <p class="text-xs text-muted-foreground">Student register, exports</p>
                </div>
            </div>
            <div class="space-y-2">
                <button @click="openModal('student-register')" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-violet-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg></span>
                    <div>
                        <span class="font-medium block">Student Register</span>
                        <span class="text-xs text-muted-foreground">Complete student list</span>
                    </div>
                </button>
                <a href="<?php echo e(route('admin.reports.export.students')); ?>" class="w-full flex items-center gap-3 rounded-lg border border-border p-3 text-left text-sm hover:bg-secondary transition-colors">
                    <span class="shrink-0 text-cyan-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg></span>
                    <div>
                        <span class="font-medium block">Students (Excel)</span>
                        <span class="text-xs text-muted-foreground">Export all students to Excel</span>
                    </div>
                </a>
            </div>
        </div>

        
        <div class="rounded-xl border border-border bg-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-rose-500/10">
                    <svg class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <h3 class="font-display text-lg font-semibold">Quick Summary</h3>
                    <p class="text-xs text-muted-foreground">Key metrics overview</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-2 rounded bg-secondary/50">
                    <span class="text-sm text-muted-foreground">Students</span>
                    <span class="font-semibold"><?php echo e(number_format($stats['total_students'] ?? 0)); ?></span>
                </div>
                <div class="flex justify-between items-center p-2 rounded bg-secondary/50">
                    <span class="text-sm text-muted-foreground">Teachers</span>
                    <span class="font-semibold"><?php echo e(number_format($stats['total_teachers'] ?? 0)); ?></span>
                </div>
                <div class="flex justify-between items-center p-2 rounded bg-secondary/50">
                    <span class="text-sm text-muted-foreground">Total Fees Collected</span>
                    <span class="font-semibold text-emerald-600">TSh <?php echo e(number_format($stats['fee_collected'] ?? 0)); ?></span>
                </div>
                <div class="flex justify-between items-center p-2 rounded bg-secondary/50">
                    <span class="text-sm text-muted-foreground">Outstanding Fees</span>
                    <span class="font-semibold text-amber-600">TSh <?php echo e(number_format($stats['fee_outstanding'] ?? 0)); ?></span>
                </div>
                <div class="flex justify-between items-center p-2 rounded bg-secondary/50">
                    <span class="text-sm text-muted-foreground">Attendance Rate</span>
                    <span class="font-semibold"><?php echo e($stats['attendance_rate'] ?? 0); ?>%</span>
                </div>
            </div>
        </div>
    </div>

    
    <div x-show="showModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
         @click.self="showModal = false">
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full max-w-lg rounded-xl border border-border bg-card p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-lg font-semibold" x-text="modalTitle"></h3>
                <button @click="showModal = false" class="rounded-lg p-1.5 text-muted-foreground hover:bg-secondary">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="formAction" method="GET" target="_blank" class="space-y-4">
                
                <template x-if="reportType === 'receipt'">
                    <div>
                        <label class="block text-sm font-medium mb-1">Payment ID</label>
                        <input type="number" name="payment_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20" placeholder="Enter payment ID">
                        <p class="mt-1 text-xs text-muted-foreground">Enter the payment ID to generate a receipt</p>
                    </div>
                </template>

                
                <template x-if="reportType === 'invoice'">
                    <div>
                        <label class="block text-sm font-medium mb-1">Student ID</label>
                        <input type="number" name="student_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20" placeholder="Enter student ID">
                    </div>
                </template>

                
                <template x-if="reportType === 'fee-statement'">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Student ID</label>
                            <input type="number" name="student_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">From Date</label>
                                <input type="date" name="from" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">To Date</label>
                                <input type="date" name="to" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                            </div>
                        </div>
                    </div>
                </template>

                
                <template x-if="reportType === 'report-card'">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Student ID</label>
                            <input type="number" name="student_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Term ID</label>
                            <input type="number" name="term_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                    </div>
                </template>

                
                <template x-if="reportType === 'class-performance'">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Class Level ID</label>
                            <input type="number" name="class_level_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Term ID</label>
                            <input type="number" name="term_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                    </div>
                </template>

                
                <template x-if="reportType === 'attendance-student'">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Student ID</label>
                            <input type="number" name="student_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Term ID</label>
                            <input type="number" name="term_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                    </div>
                </template>

                
                <template x-if="reportType === 'attendance-class'">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Class Level ID</label>
                            <input type="number" name="class_level_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Term ID</label>
                            <input type="number" name="term_id" required class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">From Date</label>
                                <input type="date" name="from" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">To Date</label>
                                <input type="date" name="to" class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-chalkboard/20">
                            </div>
                        </div>
                    </div>
                </template>

                
                <template x-if="reportType === 'student-register'">
                    <div>
                        <p class="text-sm text-muted-foreground">This will generate a PDF of all enrolled students. No additional filters required.</p>
                    </div>
                </template>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showModal = false" class="rounded-lg border border-border px-4 py-2 text-sm font-medium hover:bg-secondary transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors">
                        Generate PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function reportGenerator() {
    return {
        showModal: false,
        reportType: '',
        modalTitle: '',
        formAction: '',

        openModal(type) {
            this.reportType = type;
            const routes = {
                'receipt': { title: 'Payment Receipt', url: '<?php echo e(route("admin.reports.pdf.receipt")); ?>' },
                'invoice': { title: 'Fee Invoice', url: '<?php echo e(route("admin.reports.pdf.invoice")); ?>' },
                'fee-statement': { title: 'Fee Statement', url: '<?php echo e(route("admin.reports.pdf.fee-statement")); ?>' },
                'report-card': { title: 'Report Card', url: '<?php echo e(route("admin.reports.pdf.report-card")); ?>' },
                'class-performance': { title: 'Class Performance Report', url: '<?php echo e(route("admin.reports.pdf.class-performance")); ?>' },
                'attendance-student': { title: 'Student Attendance Report', url: '<?php echo e(route("admin.reports.pdf.attendance-student")); ?>' },
                'attendance-class': { title: 'Class Attendance Report', url: '<?php echo e(route("admin.reports.pdf.attendance-class")); ?>' },
                'student-register': { title: 'Student Register', url: '<?php echo e(route("admin.reports.pdf.student-register")); ?>' },
            };
            this.modalTitle = routes[type]?.title || 'Report';
            this.formAction = routes[type]?.url || '#';
            this.showModal = true;
        }
    };
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\shule\resources\views/admin/reports.blade.php ENDPATH**/ ?>