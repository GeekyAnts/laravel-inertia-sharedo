<?php

namespace Geekyants\ShareDialog\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitedNotificaton extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $ability;
    public $model;
    public $entity;
    public function __construct($ability, $entity, $model)
    {
        $this->ability = $ability;
        $this->model = $model;
        $this->entity = $entity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $notifiable;
        $ability = $this->ability;
        $model = $this->model;
        $entity = $this->entity;
        return (new MailMessage)->markdown('share-dialog::mail.invite-user', ['user' => $user, 'ability' => $ability, 'model' => $model, 'entity' => $entity]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
