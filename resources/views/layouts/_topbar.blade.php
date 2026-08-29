{{-- Topbar partial --}}
<header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-border bg-card/85 px-4 backdrop-blur-sm sm:px-6">
    <button @click="sidebarOpen = !sidebarOpen" class="shrink-0 rounded-md p-2 text-muted-foreground hover:bg-secondary hover:text-foreground lg:hidden">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
    </button>

    <div class="flex-1 max-w-md">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <input type="text" placeholder="Search students, teachers, fees..." class="w-full rounded-lg border border-input bg-background py-2 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground/60 focus:border-teal focus:ring-2 focus:ring-ring/30 focus:outline-none">
        </div>
    </div>

    <div class="flex items-center gap-3">
        @php
            $currentTerm = \App\Models\Term::where('is_current', true)->with('academicYear')->first();
        @endphp
        @if($currentTerm)
            <div class="hidden text-right sm:block">
                <p class="font-mono text-xs font-medium text-foreground">{{ $currentTerm->name }}</p>
                <p class="font-mono text-[10px] text-muted-foreground">{{ $currentTerm->academicYear->name }}</p>
            </div>
        @endif

        <button class="relative rounded-md p-2 text-muted-foreground hover:bg-secondary hover:text-foreground">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
        </button>

        <span class="hidden font-mono text-xs text-muted-foreground sm:block">{{ now()->format('D, d M Y') }}</span>

        <a href="{{ route('profile') }}" class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium text-muted-foreground hover:bg-secondary hover:text-foreground">
            <div class="grid h-8 w-8 place-items-center rounded-full bg-chalkboard font-display text-xs font-bold text-cream">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <span class="hidden truncate md:block">{{ $user->name ?? 'User' }}</span>
        </a>
    </div>
</header>
