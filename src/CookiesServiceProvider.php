<?php

namespace WebBestPractice\Cookies;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use WebBestPractice\Cookies\Events\Track;
use WebBestPractice\Cookies\Listeners\SendTrackEvent;
use WebBestPractice\Cookies\Services\AnalyticsService;
use WebBestPractice\Cookies\Services\ConsentService;
use WebBestPractice\Cookies\View\Components\Banner;
use WebBestPractice\Cookies\View\Components\Head;
use WebBestPractice\Cookies\View\Components\Noscript;
use WebBestPractice\Cookies\View\Components\Scripts;

class CookiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cookies.php', 'cookies');

        $this->app->singleton(AnalyticsService::class);
        $this->app->singleton(ConsentService::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookies');

        $this->loadViewComponentsAs('cookies', [
            Head::class,
            Scripts::class,
            Banner::class,
            Noscript::class,
        ]);

        Route::middleware('web')
            ->prefix('cookies')
            ->as('cookies.')
            ->group(__DIR__.'/../routes/web.php');

        Event::listen(Track::class, SendTrackEvent::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cookies.php' => config_path('cookies.php'),
            ], 'cookies-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/cookies'),
            ], 'cookies-views');

            $this->publishes([
                __DIR__.'/../resources/css' => public_path('vendor/cookies/css'),
                __DIR__.'/../resources/js' => public_path('vendor/cookies/js'),
            ], 'cookies-assets');
        }
    }
}
