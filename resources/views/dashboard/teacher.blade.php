@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="font-display text-2xl font-semibold text-foreground">Teacher Dashboard</h1>
        <p class="mt-1 text-sm text-muted-foreground">Welcome back, {{ $user->name }}!</p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-dashboard.stat-card label="My Streams" value="{{ $streams->count() }}" icon="users" color="chalkboard" />
        <x-dashboard.stat-card label="My Subjects" value="{{ count($subjectNames) }}" icon="academic" color="teal" />
        <x-dashboard.stat-card label="Students in My Classes" value="{{ number_format($totalStudents) }}" icon="users" color="gold" />
        <x-dashboard.stat-card label="Attendance Marked Today" value="{{ $todayAttendance > 0 ? 'Yes' : 'No' }}" icon="check-circle" color="{{ $todayAttendance > 0 ? 'teal' : 'clay' }}" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Chart --}}
        <div class="lg:col-span-2 rounded-xl border border-border bg-card p-5">
            <h3 class="font-display text-base font-semibold text-foreground">My Attendance Trend (30 Days)</h3>
            <p class="mt-1 text-xs text-muted-foreground">Daily attendance for your streams</p>
            <div class="mt-4 h-56">
                <canvas id="teacherAttendanceChart"></canvas>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="rounded-xl border border-border bg-card p-5">
            <h3 class="font-display text-base font-semibold text-foreground">Quick Actions</h3>
            <p class="mt-1 text-xs text-muted-foreground">Common tasks</p>
            <div class="mt-4 space-y-2">
                <a href="{{ route('teacher.attendance') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-chalkboard/10 text-chalkboard">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </span>
                    Take Attendance
                </a>
                <a href="{{ route('teacher.marks') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-gold/10 text-gold">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </span>
                    Enter Marks
                </a>
                <a href="{{ route('teacher.timetable') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-teal/10 text-teal">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </span>
                    View Timetable
                </a>
            </div>
        </div>
    </div>

    {{-- Stream attendance table --}}
    <div class="mt-6 rounded-xl border border-border bg-card">
        <div class="border-b border-border px-5 py-4">
            <h3 class="font-display text-base font-semibold text-foreground">My Streams</h3>
            <p class="mt-0.5 text-xs text-muted-foreground">Assigned streams and today's attendance status</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border text-left">
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Stream</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Class</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Students</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Today</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($streams as $stream)
                        @php
                            $studentCount = \App\Models\Student::where('stream_id', $stream->id)->count();
                            $todayMarked = \App\Models\Attendance::whereDate('date', today())->where('class_level_id', $stream->class_level_id)->count() > 0;
                        @endphp
                        <tr class="border-b border-border last:border-0 transition-colors hover:bg-secondary/50">
                            <td class="px-5 py-3 font-medium text-foreground">{{ $stream->classLevel->name }} - {{ $stream->name }}</td>
                            <td class="px-5 py-3 text-muted-foreground">{{ $stream->classLevel->name }}</td>
                            <td class="px-5 py-3 font-mono text-muted-foreground">{{ $studentCount }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $todayMarked ? 'bg-teal/10 text-teal' : 'bg-clay/10 text-clay' }}">
                                    {{ $todayMarked ? 'Marked' : 'Not Marked' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <a href="{{ route('teacher.attendance') }}" class="font-medium text-chalkboard hover:underline">Take Attendance</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-muted-foreground">No streams assigned yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('teacherAttendanceChart');
    if (!ctx) return;
    const data = @json($attendanceTrend);
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
