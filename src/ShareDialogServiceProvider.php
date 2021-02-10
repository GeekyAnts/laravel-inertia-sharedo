<?php

namespace Geekyants\ShareDialog;

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

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/./config/config.php' => config_path('share-dialog.php'),
            ], 'config');


            // Publishing the views.
            // $this->publishes([
            //     __DIR__ . '/./resources/views' => resource_path('views/vendor/share-dialog'),
            // ], 'views');

            // Publishing assets.
            $this->publishes([
                __DIR__ . '/./resources/js' => resource_path('/js/Pages/ShareDialog'),
            ], 'assets');
            $this->publishes([
                __DIR__ . '/./resources/css' => resource_path('/css/ShareDialog'),
            ], 'assets');

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/share-dialog'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
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
