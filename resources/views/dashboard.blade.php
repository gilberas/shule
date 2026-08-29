<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Welcome, {{ $user->name }}!</h3>
                    <p class="mb-2">Role: <span class="font-semibold capitalize">{{ str_replace('_', ' ', $user->role) }}</span></p>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Students</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['students'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Teachers</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['teachers'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Grades Entered</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['grades_entered'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Payments Received (TZS)</div>
                    <div class="text-2xl font-bold text-green-600">{{ number_format($stats['payments_received']) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Attendance Today</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['attendance_today'] }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
