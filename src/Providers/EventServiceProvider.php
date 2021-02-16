<?php

namespace GeekyAnts\ShareDialog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Geekyants\ShareDialog\Events\UserInvited;
use Geekyants\ShareDialog\Listeners\UserInvitedListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserInvited::class => [
            UserInvitedListener::class,
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
