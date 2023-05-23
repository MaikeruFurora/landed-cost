<?php

namespace App\Providers;

use App\Models\Detail;
use App\Observers\DetailObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    // public const TYPE_CREDIT = 'CREDIT';
    // public const TYPE_DEBIT = 'DEBIT';

    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       /* Creating a custom directive for blade. */
        Blade::directive('access', function () {
            return "<?php if (auth()->user()->type) { ?>";
        });
        Blade::directive('endaccess', function () {
            return "<?php } ?>";
        });

        Blade::directive('accounting', function () {
            return "<?php if (auth()->user()->accountingHead() || auth()->user()->type) { ?>";
        });
        Blade::directive('endaccounting', function () {
            return "<?php } ?>";
        });

    }
}
