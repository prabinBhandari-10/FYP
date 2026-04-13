<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewArticlePublishedNotification extends Notification
{
    use Queueable;

    public function __construct(public Article $article)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Article Published: ' . $this->article->title)
            ->line('A new article has been published on Lost and Found platform.')
            ->line('Article Details:')
            ->line("Title: {$this->article->title}")
            ->line("Description: {$this->article->short_description}")
            ->action('Read Article', route('articles.show', $this->article))
            ->line('Stay updated with our latest tips and guidelines!');
    }
}
