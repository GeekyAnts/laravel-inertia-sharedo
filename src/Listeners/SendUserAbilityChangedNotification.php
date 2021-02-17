<?php

namespace Geekyants\ShareDialog\Listeners;

use Geekyants\ShareDialog\Events\UserAbilityChanged;
use GeekyAnts\ShareDialog\Notifications\UserInvitedNotification;
use Geekyants\ShareDialog\Notifications\UserInvitedNotificaton;
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
        if(config('share-dialog.sendEmail'))
        $user->notify(new UserInvitedNotificaton($event->ability, $event->entity, $event->model));
    }
}
