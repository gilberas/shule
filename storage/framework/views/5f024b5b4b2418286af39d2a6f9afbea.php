<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="font-display text-2xl font-semibold text-foreground">Dashboard</h1>
        <p class="mt-1 text-sm text-muted-foreground">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentTerm): ?>
                <?php echo e($currentTerm->name); ?> &middot; <?php echo e($currentTerm->academicYear->name ?? ''); ?>

            <?php else: ?>
                No active term
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>
    </div>

    
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-border bg-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Total Students</p>
                    <p class="mt-2 font-mono text-3xl font-bold text-foreground"><?php echo e(number_format($stats['total_students'])); ?></p>
                </div>
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-chalkboard/10 text-chalkboard">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128H5.228A2 2 0 0 1 5 17.119V5a2 2 0 0 1 2-2h6M12 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <span class="font-mono text-xs text-muted-foreground"><?php echo e($stats['total_teachers']); ?> teachers</span>
            </div>
        </div>

        <div class="rounded-xl border border-border bg-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Attendance Today</p>
                    <p class="mt-2 font-mono text-3xl font-bold text-foreground"><?php echo e($stats['attendance_rate']); ?>%</p>
                </div>
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-teal/10 text-teal">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-1.5 w-full overflow-hidden rounded-full bg-secondary">
                    <div class="h-full rounded-full bg-teal transition-all" style="width: <?php echo e($stats['attendance_rate']); ?>%"></div>
                </div>
                <p class="mt-1 font-mono text-xs text-muted-foreground"><?php echo e($stats['attendance_today_present']); ?>/<?php echo e($stats['attendance_today']); ?> records</p>
            </div>
        </div>

        <div class="rounded-xl border border-border bg-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Fee Collected</p>
                    <p class="mt-2 font-mono text-3xl font-bold text-foreground">TZS <?php echo e(number_format($stats['fee_collected'] / 1000)); ?>k</p>
                </div>
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-gold/10 text-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.671 1.171 1.671.879-.659m-3.22 0 .879-.659 1.171 1.671 1.171-1.671.879.659m-3.22 0 .879-.659L15 12.372l-1.171 1.671-.879-.659M12 3v3"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-1.5 w-full overflow-hidden rounded-full bg-secondary">
                    <div class="h-full rounded-full bg-gold transition-all" style="width: <?php echo e($stats['fee_collection_rate']); ?>%"></div>
                </div>
                <p class="mt-1 font-mono text-xs text-muted-foreground"><?php echo e($stats['fee_collection_rate']); ?>% collection rate</p>
            </div>
        </div>

        <div class="rounded-xl border border-border bg-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Grades Entered</p>
                    <p class="mt-2 font-mono text-3xl font-bold text-foreground"><?php echo e(number_format($stats['grades_entered'])); ?></p>
                </div>
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-clay/10 text-clay">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <span class="font-mono text-xs text-muted-foreground"><?php echo e($stats['exams_scheduled']); ?> exams scheduled</span>
            </div>
        </div>
    </div>

    
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <div class="lg:col-span-2 rounded-xl border border-border bg-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-display text-base font-semibold text-foreground">Attendance Trend</h3>
                    <p class="mt-0.5 text-xs text-muted-foreground">Daily attendance rate over 30 days</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-muted-foreground">
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-teal"></span> 80%+</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-gold"></span> 50-79%</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-clay"></span> &lt;50%</span>
                </div>
            </div>
            <div class="mt-4 h-56">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        
        <div class="rounded-xl border border-border bg-card p-5">
            <h3 class="font-display text-base font-semibold text-foreground">Quick Actions</h3>
            <p class="mt-0.5 text-xs text-muted-foreground">Common tasks</p>
            <div class="mt-4 space-y-2">
                <a href="<?php echo e(route('admin.students')); ?>" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary group">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-chalkboard/10 text-chalkboard group-hover:bg-chalkboard/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </span>
                    Add Student
                </a>
                <a href="<?php echo e(route('admin.fees')); ?>" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary group">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-gold/10 text-gold group-hover:bg-gold/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.671 1.171 1.671.879-.659m-3.22 0 .879-.659 1.171 1.671 1.171-1.671.879.659m-3.22 0 .879-.659L15 12.372l-1.171 1.671-.879-.659M12 3v3"/></svg>
                    </span>
                    Record Payment
                </a>
                <a href="<?php echo e(route('admin.attendance')); ?>" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary group">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-teal/10 text-teal group-hover:bg-teal/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </span>
                    Mark Attendance
                </a>
                <a href="<?php echo e(route('admin.grades')); ?>" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary group">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-clay/10 text-clay group-hover:bg-clay/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </span>
                    Enter Grades
                </a>
                <a href="<?php echo e(route('admin.messages')); ?>" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary group">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-gold/10 text-gold group-hover:bg-gold/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    </span>
                    Send Message
                </a>
                <a href="<?php echo e(route('admin.reports')); ?>" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary group">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-chalkboard/10 text-chalkboard group-hover:bg-chalkboard/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </span>
                    Generate Reports
                </a>
            </div>
        </div>
    </div>

    
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        
        <div class="rounded-xl border border-border bg-card">
            <div class="border-b border-border px-5 py-4">
                <h3 class="font-display text-base font-semibold text-foreground">Class Performance</h3>
                <p class="mt-0.5 text-xs text-muted-foreground">Average score per class this term</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border text-left">
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Class</th>
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Students</th>
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Avg</th>
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $classPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-border last:border-0 transition-colors hover:bg-secondary/50">
                                <td class="px-5 py-3 font-medium text-foreground"><?php echo e($class['name']); ?></td>
                                <td class="px-5 py-3 font-mono text-muted-foreground"><?php echo e($class['students']); ?></td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-sm font-medium text-foreground"><?php echo e($class['avg']); ?>%</span>
                                        <div class="h-1.5 w-16 overflow-hidden rounded-full bg-secondary">
                                            <div class="h-full rounded-full <?php echo e($class['avg'] >= 60 ? 'bg-teal' : 'bg-clay'); ?>" style="width: <?php echo e($class['avg']); ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?php echo e($class['status'] === 'On Track' ? 'bg-teal/10 text-teal' : 'bg-clay/10 text-clay'); ?>">
                                        <?php echo e($class['status']); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4" class="px-5 py-8 text-center text-muted-foreground">No class data available</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="rounded-xl border border-border bg-card">
            <div class="border-b border-border px-5 py-4">
                <h3 class="font-display text-base font-semibold text-foreground">Recent Payments</h3>
                <p class="mt-0.5 text-xs text-muted-foreground">Latest 5 payments received</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border text-left">
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Student</th>
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Amount</th>
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Status</th>
                            <th class="px-5 py-3 font-mono text-[11px] font-medium uppercase tracking-wider text-muted-foreground">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-border last:border-0 transition-colors hover:bg-secondary/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-chalkboard/10 font-display text-xs font-bold text-chalkboard">
                                            <?php echo e(strtoupper(substr($payment['name'], 0, 1))); ?>

                                        </div>
                                        <span class="font-medium text-foreground"><?php echo e($payment['name']); ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-mono text-sm font-medium text-foreground">TZS <?php echo e($payment['amount']); ?></td>
                                <td class="px-5 py-3">
                                    <?php
                                        $statusColors = ['paid' => 'bg-teal/10 text-teal', 'partial' => 'bg-gold/10 text-gold', 'pending' => 'bg-clay/10 text-clay'];
                                    ?>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?php echo e($statusColors[$payment['status']] ?? 'bg-muted text-muted-foreground'); ?>">
                                        <?php echo e(ucfirst($payment['status'])); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-muted-foreground"><?php echo e($payment['date']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4" class="px-5 py-8 text-center text-muted-foreground">No payments recorded yet</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentActivity->count()): ?>
    <div class="mt-6 rounded-xl border border-border bg-card">
        <div class="border-b border-border px-5 py-4">
            <h3 class="font-display text-base font-semibold text-foreground">Recent Activity</h3>
        </div>
        <div class="divide-y divide-border">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 px-5 py-3">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full <?php echo e($activity['type'] === 'student' ? 'bg-chalkboard/10 text-chalkboard' : 'bg-teal/10 text-teal'); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity['type'] === 'student'): ?>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <?php else: ?>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                    <span class="flex-1 text-sm text-foreground"><?php echo e($activity['text']); ?></span>
                    <span class="font-mono text-xs text-muted-foreground"><?php echo e($activity['date']); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('attendanceChart');
    if (!ctx) return;
    const data = <?php echo json_encode($attendanceTrend, 15, 512) ?>;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'Attendance %',
                data: data.map(d => d.pct),
                backgroundColor: data.map(d => d.pct >= 80 ? '#14b8a6' : d.pct >= 50 ? '#eab308' : '#ef4444'),
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) { return context.parsed.y + '%'; }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'IBM Plex Mono', size: 10 }, maxTicksLimit: 10 }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { family: 'IBM Plex Mono', size: 10 }, callback: v => v + '%' }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\shule\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>