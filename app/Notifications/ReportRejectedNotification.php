<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Report $report)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reportType = ucfirst($this->report->type);
        
        return (new MailMessage)
            ->subject("{$reportType} Report Rejected")
            ->line("Your {$reportType} report titled '{$this->report->title}' has been rejected.")
            ->line('The report does not meet our guidelines or contains inappropriate content.')
            ->line('Report Details:')
            ->line("Title: {$this->report->title}")
            ->line("Type: {$reportType}")
            ->line("Category: {$this->report->category}")
            ->line("Location: {$this->report->location}")
            ->line('If you believe this is a mistake, you can contact our support team for clarification.')
            ->action('Contact Support', route('contact.form'))
            ->line('Thank you for using Lost and Found!');
    }
}
