<?php

namespace App\Notifications;

use App\Http\Resources\ChallengeResource;
use App\Models\Challenge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ApproveChallenge extends Notification
{
    use Queueable;

    public $challenge;

    /**
     * Create a new notification instance.
     */
    public function __construct(Challenge $challenge)
    {
        $this->challenge = $challenge;
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
            'challenge' => new ChallengeResource($this->challenge),
            'notification' => [
                'severity' => 'success',
                'summary' => 'Verify Challenge',
                'detail' => 'Your ' . $this->challenge->name . ' has been accepted',
            ]
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
