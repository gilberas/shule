@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="font-display text-2xl font-semibold text-foreground">Parent Dashboard</h1>
        <p class="mt-1 text-sm text-muted-foreground">Track your child's progress</p>
    </div>

    {{-- Child selector --}}
    @if($children->count() > 1)
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach($children as $c)
                <a href="{{ route('parent.dashboard', ['child_id' => $c->id]) }}"
                   class="rounded-lg border px-4 py-2 text-sm font-medium transition-colors {{ $child && $child->id === $c->id ? 'border-chalkboard bg-chalkboard text-cream' : 'border-border text-muted-foreground hover:bg-secondary hover:text-foreground' }}">
                    {{ $c->first_name }} {{ $c->last_name }}
                </a>
            @endforeach
        </div>
    @endif

    @if($child)
        {{-- Stat cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-dashboard.stat-card label="Attendance This Month" value="{{ $attendancePct }}%" icon="check-circle" color="{{ $attendancePct >= 80 ? 'teal' : 'clay' }}" />
            <x-dashboard.stat-card label="Latest Term Average" value="{{ $termAverage }}%" icon="clipboard" color="{{ $termAverage >= 60 ? 'teal' : 'gold' }}" />
            <x-dashboard.stat-card label="Fee Balance" value="TZS {{ number_format($feeBalance) }}" icon="currency" color="{{ $feeBalance > 0 ? 'clay' : 'teal' }}" />
            <x-dashboard.stat-card label="Upcoming Exams" value="{{ $upcomingExams }}" icon="document" color="chalkboard" />
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Attendance chart --}}
            <div class="lg:col-span-2 rounded-xl border border-border bg-card p-5">
                <h3 class="font-display text-base font-semibold text-foreground">{{ $child->first_name }}'s Attendance Trend</h3>
                <p class="mt-1 text-xs text-muted-foreground">Daily attendance over the term</p>
                <div class="mt-4 h-56">
                    <canvas id="parentAttendanceChart"></canvas>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="rounded-xl border border-border bg-card p-5">
                <h3 class="font-display text-base font-semibold text-foreground">Quick Actions</h3>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('parent.fees') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                        <span class="grid h-8 w-8 place-items-center rounded-md bg-gold/10 text-gold">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.671 1.171 1.671.879-.659m-3.22 0 .879-.659 1.171 1.671 1.171-1.671.879.659m-3.22 0 .879-.659L15 12.372l-1.171 1.671-.879-.659M12 3v3"/></svg>
                        </span>
                        Pay Fees Now
                    </a>
                    <a href="{{ route('parent.children') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                        <span class="grid h-8 w-8 place-items-center rounded-md bg-chalkboard/10 text-chalkboard">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128H5.228A2 2 0 0 1 5 17.119V5a2 2 0 0 1 2-2h6M12 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        </span>
                        View Children
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent grades --}}
        <div class="mt-6 rounded-xl border border-border bg-card">
            <div class="border-b border-border px-5 py-4">
                <h3 class="font-display text-base font-semibold text-foreground">{{ $child->first_name }}'s Recent Grades</h3>
                <p class="mt-0.5 text-xs text-muted-foreground">Latest marks this term</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border text-left">
                            <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Subject</th>
                            <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Latest Mark</th>
                            <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentGrades as $grade)
                            <tr class="border-b border-border last:border-0 transition-colors hover:bg-secondary/50">
                                <td class="px-5 py-3 font-medium text-foreground">{{ $grade['subject'] }}</td>
                                <td class="px-5 py-3 font-mono font-medium text-foreground">{{ $grade['score'] }}%</td>
                                <td class="px-5 py-3">
                                    @php
                                        $gradeColors = ['A' => 'bg-teal/10 text-teal', 'B' => 'bg-teal/10 text-teal', 'C' => 'bg-gold/10 text-gold', 'D' => 'bg-clay/10 text-clay', 'E' => 'bg-clay/10 text-clay', 'F' => 'bg-clay/10 text-clay'];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $gradeColors[$grade['grade']] ?? 'bg-muted text-muted-foreground' }}">
                                        {{ $grade['grade'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-muted-foreground">No grades recorded yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="rounded-xl border border-border bg-card p-8 text-center">
            <p class="text-muted-foreground">No children linked to your account. Please contact the school admin.</p>
        </div>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('parentAttendanceChart');
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
