<?php

namespace Language\Iso;

use App\Facades\ISO639;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Language\Iso\Contract\ISOConfigInterface;
use Language\Iso\Contract\ISOLoaderInterface;
use Language\Iso\Facades\ISO;


class ISOServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('iso-language.published_route')) {
            $this->registerRoutes();
        }


        Validator::extend('language', function ($attribute, $value, $parameters, $validator) {
            return match ($parameters[0] ?? 'default') {
                'all' => ISO::isISOCode($value),
                'default' => ISO::isDefault($value),
                'available' => ISO::isAvailableLanguage($value),
                default => false
            };
        });
    }

    public function register(): void
    {
        $this->app->bind(ISOConfigInterface::class, fn() => new ISOConfig(config('iso-language')));
        $this->app->bind(ISOLoaderInterface::class, fn() => new ISOLoader());

        $this->app->singleton('iso', function ($app) {
            return new ISOService(
                $app->make(ISOConfigInterface::class),
                $app->make(ISOLoaderInterface::class),
                $app->make(Cache::class)
            );
        });
    }

    protected function registerRoutes(): void
    {
        Route::prefix(config('iso-language.prefix'))->group(function () {
            Route::middleware(config('iso-language.middleware', []))->group(function () {
                Route::get('languages', fn() => ISO::getAllLanguages());
                Route::get('available-languages', fn() => ISO::getAvailableLanguages());
            });
        });
    }
}
