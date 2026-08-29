@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="font-display text-2xl font-semibold text-foreground">Student Dashboard</h1>
        <p class="mt-1 text-sm text-muted-foreground">Welcome, {{ $user->name }}!</p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-dashboard.stat-card label="My Attendance This Month" value="{{ $attendancePct }}%" icon="check-circle" color="{{ $attendancePct >= 80 ? 'teal' : 'clay' }}" />
        <x-dashboard.stat-card label="My Term Average" value="{{ $termAverage }}%" icon="clipboard" color="{{ $termAverage >= 60 ? 'teal' : 'gold' }}" />
        <x-dashboard.stat-card label="My Fee Balance" value="TZS {{ number_format($feeBalance) }}" icon="currency" color="{{ $feeBalance > 0 ? 'clay' : 'teal' }}" />
        <x-dashboard.stat-card label="Next Exam" value="{{ $nextExam ? $nextExam->exam_date : 'None' }}" icon="document" color="chalkboard" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Attendance chart --}}
        <div class="lg:col-span-2 rounded-xl border border-border bg-card p-5">
            <h3 class="font-display text-base font-semibold text-foreground">My Attendance Trend</h3>
            <p class="mt-1 text-xs text-muted-foreground">Your daily attendance over the term</p>
            <div class="mt-4 h-56">
                <canvas id="studentAttendanceChart"></canvas>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="rounded-xl border border-border bg-card p-5">
            <h3 class="font-display text-base font-semibold text-foreground">Quick Actions</h3>
            <div class="mt-4 space-y-2">
                <a href="{{ route('student.timetable') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-chalkboard/10 text-chalkboard">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </span>
                    View Timetable
                </a>
                <a href="{{ route('student.grades') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-gold/10 text-gold">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </span>
                    View Report Card
                </a>
                <a href="{{ route('student.attendance') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-teal/10 text-teal">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </span>
                    View Attendance
                </a>
            </div>
        </div>
    </div>

    {{-- Subject grades --}}
    <div class="mt-6 rounded-xl border border-border bg-card">
        <div class="border-b border-border px-5 py-4">
            <h3 class="font-display text-base font-semibold text-foreground">My Grades This Term</h3>
            <p class="mt-0.5 text-xs text-muted-foreground">Marks per subject</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border text-left">
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Subject</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Latest Mark</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Average</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjectGrades as $sg)
                        <tr class="border-b border-border last:border-0 transition-colors hover:bg-secondary/50">
                            <td class="px-5 py-3 font-medium text-foreground">{{ $sg['subject'] }}</td>
                            <td class="px-5 py-3 font-mono font-medium text-foreground">{{ $sg['latest'] }}%</td>
                            <td class="px-5 py-3 font-mono text-muted-foreground">{{ $sg['avg'] }}%</td>
                            <td class="px-5 py-3">
                                @php
                                    $gradeColors = ['A' => 'bg-teal/10 text-teal', 'B' => 'bg-teal/10 text-teal', 'C' => 'bg-gold/10 text-gold', 'D' => 'bg-clay/10 text-clay', 'E' => 'bg-clay/10 text-clay', 'F' => 'bg-clay/10 text-clay'];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $gradeColors[$sg['grade']] ?? 'bg-muted text-muted-foreground' }}">
                                    {{ $sg['grade'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-muted-foreground">No grades recorded yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('studentAttendanceChart');
    if (!ctx) return;
    const data = @json($attendanceTrend);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'Attendance %',
                data: data.map(d => d.pct),
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20,184,166,0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 2,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ctx.parsed.y + '%' } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'IBM Plex Mono', size: 10 }, maxTicksLimit: 10 } },
                y: { beginAtZero: true, max: 100, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { family: 'IBM Plex Mono', size: 10 }, callback: v => v + '%' } }
            }
        }
    });
});
</script>
@endpush
