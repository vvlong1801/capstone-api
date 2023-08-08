<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnApproveChallenge extends Notification
{
    use Queueable;
    protected $challenge;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($challenge, $message)
    {
        $this->challenge = $challenge;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Notification UnAccept Challenge')
            ->greeting('UnAccept Challenge!')
            ->line('We do not accept ' . $this->challenge->name . ' challenge you created')
            ->line('Here are the reasons that we do not accept')
            ->line($this->message);
    }
}
