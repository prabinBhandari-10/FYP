<?php

namespace App\Listeners;

use App\Events\ReportSubmitted;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\ReportSubmittedNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendReportSubmissionEmail
{
    public function handle(ReportSubmitted $event): void
    {
        try {
            // Send email to the report submitter
            $event->user->notify(new ReportSubmittedNotification($event->report));
        } catch (Throwable $e) {
            Log::error('Failed to send report submission email.', [
                'user_id' => $event->user->id,
                'report_id' => $event->report->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Create admin notifications for all admins
        try {
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'new_report',
                    'title' => 'New Report Submitted',
                    'message' => "A new {$event->report->type} item report \"{$event->report->title}\" has been submitted by {$event->user->name}. Please review and approve/reject it.",
                    'related_report_id' => $event->report->id,
                    'is_read' => false,
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Failed to create admin notifications for report submission.', [
                'report_id' => $event->report->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
