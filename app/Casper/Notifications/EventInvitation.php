<?php

namespace App\Casper\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Casper\Model\EventInvitation as Invitation;

class EventInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\Casper\Model\EventInvitation
     */
    protected $invitation;

    /**
     * Create a new notification instance.
     *
     * @param \App\Casper\Model\EventInvitation $invitation
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
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
        return (new MailMessage)
                    ->line('You have been invited to event.')
                    ->line($this->invitation->event->name)
                    ->action('Join Event', url('/'));
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
