<?php

namespace App\Notifications\Auth;

use App\Models\Auth\UserInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notifiable;

class Invitation extends Notification
{
    /**
     * The password reset token.
     *
     * @var UserInvitation
     */
    use Notifiable;

    public $invitation;

    /**
     * Create a notification instance.
     *
     * @param UserInvitation $invitation
     */
    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->from('whosendall@gmail.com','Hussein Muhtadee')
            ->line(trans('auth.invitation.message_1'))
            ->action(trans('auth.invitation.button'), route('register', $this->invitation->token))
            ->line(trans('auth.invitation.message_2'));
    }
}
