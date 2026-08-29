@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="font-display text-2xl font-semibold text-foreground">Accountant Dashboard</h1>
        <p class="mt-1 text-sm text-muted-foreground">Fee collection and payment overview</p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-dashboard.stat-card label="Total Collected This Term" value="TZS {{ number_format($totalCollected) }}" icon="currency" color="teal" />
        <x-dashboard.stat-card label="Outstanding Balance" value="TZS {{ number_format($outstanding) }}" icon="currency" color="clay" />
        <x-dashboard.stat-card label="Invoices Overdue" value="{{ $overdueCount }}" icon="document" color="gold" />
        <x-dashboard.stat-card label="Payments Today" value="{{ $todayPayments }}" icon="check-circle" color="chalkboard" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Fee collection chart --}}
        <div class="lg:col-span-2 rounded-xl border border-border bg-card p-5">
            <h3 class="font-display text-base font-semibold text-foreground">Fee Collection Trend</h3>
            <p class="mt-1 text-xs text-muted-foreground">Weekly collection over the term (TZS thousands)</p>
            <div class="mt-4 h-56">
                <canvas id="feeCollectionChart"></canvas>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="rounded-xl border border-border bg-card p-5">
            <h3 class="font-display text-base font-semibold text-foreground">Quick Actions</h3>
            <p class="mt-1 text-xs text-muted-foreground">Fee management</p>
            <div class="mt-4 space-y-2">
                <a href="{{ route('admin.fees') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-chalkboard/10 text-chalkboard">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.671 1.171 1.671.879-.659m-3.22 0 .879-.659 1.171 1.671 1.171-1.671.879.659m-3.22 0 .879-.659L15 12.372l-1.171 1.671-.879-.659M12 3v3"/></svg>
                    </span>
                    Record Payment
                </a>
                <a href="{{ route('admin.fees.structures') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-gold/10 text-gold">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </span>
                    Generate Invoices
                </a>
                <a href="{{ route('admin.fees.invoices') }}" class="flex items-center gap-3 rounded-lg border border-border px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-teal/10 text-teal">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </span>
                    View Invoices
                </a>
            </div>
        </div>
    </div>

    {{-- Overdue invoices table --}}
    <div class="mt-6 rounded-xl border border-border bg-card">
        <div class="border-b border-border px-5 py-4">
            <h3 class="font-display text-base font-semibold text-foreground">Overdue Invoices</h3>
            <p class="mt-0.5 text-xs text-muted-foreground">Students with pending payments</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border text-left">
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Student</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Class</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Amount Due</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Days</th>
                        <th class="px-5 py-3 font-mono text-xs font-medium uppercase tracking-wider text-muted-foreground">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueInvoices as $invoice)
                        <tr class="border-b border-border last:border-0 transition-colors hover:bg-secondary/50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-chalkboard/10 font-display text-xs font-bold text-chalkboard">
                                        {{ strtoupper(substr($invoice['student'], 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-foreground">{{ $invoice['student'] }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-muted-foreground">{{ $invoice['class'] }}</td>
                            <td class="px-5 py-3 font-mono font-medium text-foreground">TZS {{ $invoice['amount'] }}</td>
                            <td class="px-5 py-3 font-mono text-muted-foreground">{{ $invoice['days'] }}d</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-clay/10 text-clay">Overdue</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-muted-foreground">No overdue invoices</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('feeCollectionChart');
    if (!ctx) return;
    const data = @json($feeTrend);
    if (!data || data.length === 0) {
        ctx.parentElement.innerHTML = '<div class="flex h-full items-center justify-center text-sm text-muted-foreground">No fee data for this term</div>';
        return;
    }
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.week),
            datasets: [{
                label: 'Collected (TZS k)',
                data: data.map(d => d.collected),
                backgroundColor: '#14b8a6',
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => 'TZS ' + ctx.parsed.y.toLocaleString() + 'k' } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'IBM Plex Mono', size: 10 } } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { family: 'IBM Plex Mono', size: 10 }, callback: v => 'TZS ' + v + 'k' } }
            }
        }
    });
});
</script>
@endpush
