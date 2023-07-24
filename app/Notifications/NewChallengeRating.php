<?php

namespace App\Notifications;

use App\Models\Challenge;
use App\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewChallengeRating extends Notification
{
    use Queueable;

    public $challenge;
    public $rate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Challenge $challenge, Rating $rate)
    {
        $this->challenge = $challenge;
        $this->rate = $rate;
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
            'challenge' => $this->challenge->id,
            'rate' => $this->rate,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'severity' => 'info',
            'summary' => 'New Rating',
            'detail' => $this->rate->rateBy->name . ' rate ' . $this->rate->value . ' star for ' . $this->challenge->name,
        ]);
    }
}
