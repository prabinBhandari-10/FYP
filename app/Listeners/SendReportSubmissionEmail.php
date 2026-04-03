<?php

namespace App\Listeners;

use App\Events\ReportSubmitted;
use App\Notifications\ReportSubmittedNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendReportSubmissionEmail
{
    public function handle(ReportSubmitted $event): void
    {
        try {
            $event->user->notify(new ReportSubmittedNotification($event->report));
        } catch (Throwable $e) {
            Log::error('Failed to send report submission email.', [
                'user_id' => $event->user->id,
                'report_id' => $event->report->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
