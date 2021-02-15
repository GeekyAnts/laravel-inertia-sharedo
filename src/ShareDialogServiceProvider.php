<?php

namespace Geekyants\ShareDialog;

use Geekyants\ShareDialog\Middleware\RestrictEntities;
use Illuminate\Support\ServiceProvider;


class ShareDialogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'share-dialog');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'share-dialog');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/./routes/web.php');

        app('router')->aliasMiddleware('restrict-entities', RestrictEntities::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/./config/config.php' => config_path('share-dialog.php'),
            ], 'config');


            // Registering package commands.
            $this->commands([
                ShareDialogInstallCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/./config/config.php', 'share-dialog');

        // Register the main class to use with the facade
        $this->app->singleton('share-dialog', function () {
            return new ShareDialog;
        });
    }
}
