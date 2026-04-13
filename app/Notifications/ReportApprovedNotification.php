<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportApprovedNotification extends Notification
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
            ->subject("{$reportType} Report Approved")
            ->line("Your {$reportType} report titled '{$this->report->title}' has been approved.")
            ->line('Your report is now visible to all users on the platform.')
            ->line('Report Details:')
            ->line("Title: {$this->report->title}")
            ->line("Type: {$reportType}")
            ->line("Category: {$this->report->category}")
            ->line("Location: {$this->report->location}")
            ->line('You can track your report status and view any matching items or claims from your dashboard.')
            ->action('View Your Report', route('reports.track.show', ['reportUid' => $this->report->report_uid]))
            ->line('Thank you for using Lost and Found!');
    }
}
