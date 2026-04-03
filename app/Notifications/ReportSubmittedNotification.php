<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportSubmittedNotification extends Notification
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
        return (new MailMessage)
            ->subject('Report Submitted Successfully')
            ->line('Your report has been successfully submitted to our Lost and Found system.')
            ->line('Our team will review your report and keep you updated if any matching item is found.')
            ->line('If you would like to contact the admin for urgent queries or support, you can reach out using the contact details below.')
            ->line('Admin Contact Information:')
            ->line('Email: admin@lostandfound.com')
            ->line('Phone: +977-98XXXXXXXX')
            ->line('Description:')
            ->line('Feel free to contact the admin for any issues related to your report, claim process, or urgent help.');
    }
}
