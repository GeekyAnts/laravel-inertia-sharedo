<?php

namespace Geekyants\ShareDialog\Listeners;

use Geekyants\ShareDialog\Events\UserInvited;
use GeekyAnts\ShareDialog\Notifications\UserInvitedNotification;
use Geekyants\ShareDialog\Notifications\UserInvitedNotificaton;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserInvitedListener
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
    public function handle(UserInvited $event)
    {
        $user = $event->user;
        $user->notify(new UserInvitedNotificaton($event->ability, $event->entity, $event->model));
    }
}
