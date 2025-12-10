<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
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
    public function toMail($notifiable)
    {
        $url = url("api/reset-password/{$this->token}?email={$notifiable->email}");

        return (new MailMessage)
            ->subject('🔐 Reset Your Password') //
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We received a request to reset your password.')
            ->line('Click the button below to choose a new password:')
            ->action('Reset My Password', $url)
            ->line('This password reset link will expire in 60 minutes')
            ->line('If you did not request this, please ignore this email.')
            ->salutation('Kind regards, Your App Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
