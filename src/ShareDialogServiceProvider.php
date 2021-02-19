<?php

namespace Geekyants\ShareDialog;



use Geekyants\ShareDialog\Middleware\RestrictEntities;
use Illuminate\Support\ServiceProvider;
use Geekyants\ShareDialog\Providers\EventServiceProvider;
use Geekyants\ShareDialog\Middleware\ShareInertiaData;
use Laravel\Ui\UiCommand;
use Geekyants\ShareDialog\ShareDialogPreset;

class ShareDialogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {



        UiCommand::macro('share-dialog', function ($command) {
          ShareDialogPreset::install();

            $command->info('Inertia.js scaffolding installed successfully.');
            $command->info('Please run "composer update, npm install && npm run dev" to compile your fresh scaffolding.');
        });

     
  
        $this->bootInertia();


       

        $this->loadViewsFrom(__DIR__ . '/./resources/views', 'share-dialog');
        $this->loadMigrationsFrom(__DIR__.'/./migrations');
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
         
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {


       //register the event service provider
        $this->app->register(EventServiceProvider::class);

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/./config/config.php', 'share-dialog');




        // Register the main class to use with the facade
        $this->app->singleton('share-dialog', function () {
            return new ShareDialog;
        });
    }
    protected function bootInertia()
    {
       
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', ShareInertiaData::class);
    
    }
}
