<?php

namespace App\Notifications;

use App\Models\Challenge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApproveChallenge extends Notification
{
    use Queueable;

    public $challenge;
    public $approve;
    /**
     * Create a new notification instance.
     */
    public function __construct(Challenge $challenge, $approve)
    {
        $this->challenge = $challenge;
        $this->approve = $approve;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'challenge_id' => $this->challenge->id,
            'challenge_name' => $this->challenge->name,
            'approve' => $this->approve,
            'message' => $this->approve ? 'your challenge approved' : 'your challenge unapproved, Please check the email for the reason'
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'severity' => 'success',
            'summary' => 'Verify Challenge',
            'detail' => 'Your ' . $this->challenge->name . ' has been accepted',
        ]);
    }
}
