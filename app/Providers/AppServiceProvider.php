<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\CurrencyHelper;

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
        Blade::component('app-layout', \App\View\Components\AppLayout::class);
        // Thêm directive để định dạng tiền tệ VNĐ
        Blade::directive('vnd', function ($expression) {
            return "<?php echo App\Helpers\CurrencyHelper::formatVND($expression); ?>";
        });
    }
}
