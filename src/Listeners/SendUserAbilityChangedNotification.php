<?php

namespace Geekyants\Sharedo\Listeners;

use Geekyants\Sharedo\Events\UserAbilityChanged;
use GeekyAnts\Sharedo\Notifications\UserInvitedNotification;
use Geekyants\Sharedo\Notifications\UserInvitedNotificaton;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserAbilityChangedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserInvited  $event
     * @return void
     */
    public function handle(UserAbilityChanged $event)
    {
        $user = $event->user;
        $user->notify(new UserInvitedNotificaton($event->ability, $event->entity, $event->model));
    }
}
