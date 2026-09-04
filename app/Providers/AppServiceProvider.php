<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole() === false) {
            $req = request();
            $host = $req->header('x-forwarded-host') ?: $req->header('host');
            if ($host) {
                $scheme = $req->secure() ? 'https' : 'http';
                $url = $scheme . '://' . $host;
                config(['app.url' => $url]);
                \Illuminate\Support\Facades\URL::forceRootUrl($url);
                if ($req->secure()) {
                    \Illuminate\Support\Facades\URL::forceScheme('https');
                }
            }
        }

        Blade::directive('relvite', function (string $expression) {
            return "<?php
                \$__viteHtml = app(\Illuminate\Foundation\Vite::class)->content({$expression});
                echo preg_replace('#https?://[^/]+/build/#', '/build/', \$__viteHtml);
            ?>";
        });

        Livewire::component('manage-fees', \App\Livewire\ManageFees::class);
        Livewire::component('manage-students', \App\Livewire\ManageStudents::class);
        Livewire::component('manage-teachers', \App\Livewire\ManageTeachers::class);
        Livewire::component('manage-grades', \App\Livewire\ManageGrades::class);
        Livewire::component('manage-attendance', \App\Livewire\ManageAttendance::class);
        Livewire::component('manage-exams', \App\Livewire\ManageExams::class);
        Livewire::component('manage-timetable', \App\Livewire\ManageTimetable::class);
        Livewire::component('manage-parents', \App\Livewire\ManageParents::class);
        Livewire::component('manage-messages', \App\Livewire\ManageMessages::class);
        Livewire::component('manage-academic-yet-terms', \App\Livewire\ManageAcademicYearsAndTerms::class);
        Livewire::component('manage-library', \App\Livewire\ManageLibrary::class);
        Livewire::component('manage-hostel', \App\Livewire\ManageHostel::class);
        Livewire::component('manage-transportation', \App\Livewire\ManageTransportation::class);
    }
}
