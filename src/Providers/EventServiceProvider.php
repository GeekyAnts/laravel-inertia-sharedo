<?php

namespace GeekyAnts\ShareDialog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Geekyants\ShareDialog\Events\UserAbilityChanged;
use Geekyants\ShareDialog\Listeners\SendUserAbilityChangedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserAbilityChanged::class => [
            SendUserAbilityChangedNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
