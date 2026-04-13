<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    public function __construct(public User $newUser)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New User Registration')
            ->line('A new user has registered on Lost and Found platform.')
            ->line('User Details:')
            ->line("Name: {$this->newUser->name}")
            ->line("Email: {$this->newUser->email}")
            ->line("Registered At: {$this->newUser->created_at->format('Y-m-d H:i:s')}")
            ->action('View User Profile', route('admin.users.show', $this->newUser))
            ->line('You can review user details and manage their account from the admin dashboard.');
    }
}
