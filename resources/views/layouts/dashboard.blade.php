<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — TSMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..900&family=Inter:wght@300..700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-background text-foreground" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-chalkboard/50 backdrop-blur-sm lg:hidden" x-transition:enter="transition-opacity duration-200" x-transition:leave="transition-opacity duration-200" @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-border bg-card transition-transform duration-200 lg:translate-x-0" x-cloak>
        {{-- Logo --}}
        <div class="flex h-16 items-center gap-2.5 border-b border-border px-5">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-chalkboard font-display text-base font-bold text-cream">T</span>
            <div class="min-w-0">
                <span class="block truncate font-display text-lg font-semibold leading-none">TSMS</span>
                <span class="block truncate font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Tanzania</span>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            @php
                $user = auth()->user();
                $role = $user->role ?? 'student';
                $currentRoute = request()->route()?->getName() ?? '';

                $navItems = [
                    'super_admin' => [
                        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home'],
                        ['label' => 'Students', 'route' => 'admin.students', 'icon' => 'users'],
                        ['label' => 'Teachers', 'route' => 'admin.teachers', 'icon' => 'academic'],
                        ['label' => 'Grades', 'route' => 'admin.grades', 'icon' => 'clipboard'],
                        ['label' => 'Attendance', 'route' => 'admin.attendance', 'icon' => 'check-circle'],
                        ['label' => 'Fees', 'route' => 'admin.fees', 'icon' => 'currency'],
                        ['label' => 'Exams', 'route' => 'admin.exams', 'icon' => 'document'],
                        ['label' => 'Timetable', 'route' => 'admin.timetable', 'icon' => 'calendar'],
                        ['label' => 'Parents', 'route' => 'admin.parents', 'icon' => 'people'],
                        ['label' => 'Library', 'route' => 'admin.library', 'icon' => 'book'],
                        ['label' => 'Transport', 'route' => 'admin.transportation', 'icon' => 'bus'],
                        ['label' => 'Hostel', 'route' => 'admin.hostel', 'icon' => 'building'],
                        ['label' => 'Messages', 'route' => 'admin.messages', 'icon' => 'mail'],
                        ['label' => 'Academic Years', 'route' => 'admin.academic.years-terms', 'icon' => 'academic'],
                    ],
                    'admin' => [
                        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home'],
                        ['label' => 'Students', 'route' => 'admin.students', 'icon' => 'users'],
                        ['label' => 'Teachers', 'route' => 'admin.teachers', 'icon' => 'academic'],
                        ['label' => 'Grades', 'route' => 'admin.grades', 'icon' => 'clipboard'],
                        ['label' => 'Attendance', 'route' => 'admin.attendance', 'icon' => 'check-circle'],
                        ['label' => 'Fees', 'route' => 'admin.fees', 'icon' => 'currency'],
                        ['label' => 'Exams', 'route' => 'admin.exams', 'icon' => 'document'],
                        ['label' => 'Timetable', 'route' => 'admin.timetable', 'icon' => 'calendar'],
                        ['label' => 'Parents', 'route' => 'admin.parents', 'icon' => 'people'],
                        ['label' => 'Messages', 'route' => 'admin.messages', 'icon' => 'mail'],
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
                ];

                $items = $navItems[$role] ?? $navItems['student'];
            @endphp

            <ul class="space-y-1">
                @foreach ($items as $item)
                    @php
                        $isActive = str_contains($currentRoute, $item['route']);
                    @endphp
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $isActive ? 'bg-chalkboard/8 text-chalkboard' : 'text-muted-foreground hover:bg-secondary hover:text-foreground' }}">
                            <span class="shrink-0">
                                @switch($item['icon'])
                                    @case('home')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                    @break
                                    @case('users')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128H5.228A2 2 0 0 1 5 17.119V5a2 2 0 0 1 2-2h6M12 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                    @break
                                    @case('academic')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                    @break
                                    @case('clipboard')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                                    @break
                                    @case('check-circle')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    @break
                                    @case('currency')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.671 1.171 1.671.879-.659m-3.22 0 .879-.659 1.171 1.671 1.171-1.671.879.659m-3.22 0 .879-.659L15 12.372l-1.171 1.671-.879-.659M12 3v3m6.239 2.818-.879.659M18 12.372l-1.171 1.671-.879-.659"/></svg>
                                    @break
                                    @case('document')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                    @break
                                    @case('calendar')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                    @break
                                    @case('people')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                                    @break
                                    @case('book')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                    @break
                                    @case('bus')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75m-7.5-3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                    @break
                                    @case('building')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
                                    @break
                                    @case('mail')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                    @break
                                @endswitch
                            </span>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- User info at bottom --}}
        <div class="border-t border-border p-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-chalkboard/10 font-display text-sm font-bold text-chalkboard">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-foreground">{{ $user->name }}</p>
                    <p class="truncate text-xs text-muted-foreground capitalize">{{ str_replace('_', ' ', $role) }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="shrink-0 rounded-md p-1.5 text-muted-foreground hover:bg-secondary hover:text-foreground" title="Log out">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="lg:pl-64">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-border bg-card/85 px-4 backdrop-blur-sm sm:px-6">
            {{-- Mobile menu button --}}
            <button @click="sidebarOpen = !sidebarOpen" class="shrink-0 rounded-md p-2 text-muted-foreground hover:bg-secondary hover:text-foreground lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>

            {{-- Search --}}
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    <input type="text" placeholder="Search students, teachers, fees..." class="w-full rounded-lg border border-input bg-background py-2 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground/60 focus:border-teal focus:ring-2 focus:ring-ring/30 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Term info --}}
                <div class="hidden text-right sm:block">
                    @php
                        $currentTerm = \App\Models\Term::where('is_current', true)->with('academicYear')->first();
                    @endphp
                    @if($currentTerm)
                        <p class="font-mono text-xs font-medium text-foreground">{{ $currentTerm->name }}</p>
                        <p class="font-mono text-[10px] text-muted-foreground">{{ $currentTerm->academicYear->name }}</p>
                    @endif
                </div>

                {{-- Notifications --}}
                <button class="relative rounded-md p-2 text-muted-foreground hover:bg-secondary hover:text-foreground">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                </button>

                {{-- Date --}}
                <span class="hidden font-mono text-xs text-muted-foreground sm:block">{{ now()->format('D, d M Y') }}</span>

                {{-- Profile --}}
                <a href="{{ route('profile') }}" class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium text-muted-foreground hover:bg-secondary hover:text-foreground">
                    <div class="grid h-8 w-8 place-items-center rounded-full bg-chalkboard font-display text-xs font-bold text-cream">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="hidden truncate md:block">{{ $user->name }}</span>
                </a>
            </div>
        </header>

        {{-- Page content --}}
        <main class="p-4 sm:p-6">
            @if (isset($header))
                <div class="mb-6">
                    {{ $header }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
