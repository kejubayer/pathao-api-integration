<?php

namespace Kejubayer\PathaoIntegration;

use Illuminate\Support\ServiceProvider;
use Kejubayer\PathaoIntegration\Contracts\PathaoInterface;
use Kejubayer\PathaoIntegration\Services\PathaoService;

class PathaoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/pathao.php',
            'pathao'
        );

        $this->app->singleton('pathao', function () {
            return new PathaoService();
        });

        $this->app->alias('pathao', PathaoInterface::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/pathao.php' => config_path('pathao.php'),
        ], 'pathao-config');
    }
}
