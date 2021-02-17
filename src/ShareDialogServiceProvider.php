<?php

namespace Geekyants\ShareDialog;

use Geekyants\ShareDialog\Middleware\RestrictEntities;
use Illuminate\Support\ServiceProvider;
use Geekyants\ShareDialog\Providers\EventServiceProvider;
use Inertia\Inertia;
use Illuminate\Http\Request;



class ShareDialogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

     
        Inertia::share('flash',function(Request $request) {
            return [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ];
        },
    );



        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'share-dialog');
        $this->loadViewsFrom(__DIR__ . '/./resources/views', 'share-dialog');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/./routes/web.php');
        app('router')->aliasMiddleware('restrict-entities', RestrictEntities::class);


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/./config/config.php' => config_path('share-dialog.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/./resources/views/mail' => resource_path('views/vendor/share-dialog/mail'),
            ], 'mail');



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



        $this->app->register(EventServiceProvider::class);

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/./config/config.php', 'share-dialog');


        //register the event service provider

        // Register the main class to use with the facade
        $this->app->singleton('share-dialog', function () {
            return new ShareDialog;
        });
    }
}
