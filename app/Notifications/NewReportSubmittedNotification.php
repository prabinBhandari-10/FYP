<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReportSubmittedNotification extends Notification
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
            ->subject("New {$reportType} Report Submitted")
            ->line("A new {$reportType} report has been submitted for your review.")
            ->line('Report Details:')
            ->line("Title: {$this->report->title}")
            ->line("Type: {$reportType}")
            ->line("Category: {$this->report->category}")
            ->line("Location: {$this->report->location}")
            ->line("Reporter: {$this->report->reporter_name}")
            ->line("Email: {$this->report->reporter_email}")
            ->line("Status: Pending Review")
            ->action('Review Report', route('admin.reports.show', $this->report))
            ->line('Please review and approve or reject this report.');
    }
}
