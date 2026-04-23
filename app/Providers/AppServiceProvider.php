<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Blade directive untuk foto URL yang bener
        Blade::directive('fotoUrl', function ($expression) {
            return "<?php echo $expression ? Storage::url($expression) : ''; ?>";
        });
    }
}
