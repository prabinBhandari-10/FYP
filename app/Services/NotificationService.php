<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function notifySimilarItemFound(User $user, $report, $matchingReport)
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'similar_item',
            'title' => 'Similar Item Found!',
            'message' => "Found a {$matchingReport->category} similar to your search: \"{$matchingReport->title}\"",
            'related_report_id' => $report->id,
        ]);

        // Send email notification
        $this->sendEmailNotification($user, $notification, 'Similar Item Found', $matchingReport);

        return $notification;
    }

    public function notifyClaimReceived(User $user, $claim)
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'claim_received',
            'title' => 'New Claim on Your Item',
            'message' => "Someone has claimed your item: \"{$claim->report->title}\"",
            'related_claim_id' => $claim->id,
            'related_report_id' => $claim->report->id,
        ]);

        $this->sendEmailNotification($user, $notification, 'New Claim on Your Item', $claim);

        return $notification;
    }

    public function notifyClaimApproved(User $user, $claim)
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'claim_approved',
            'title' => 'Claim Approved!',
            'message' => "Your claim for \"{$claim->report->title}\" has been approved!",
            'related_claim_id' => $claim->id,
            'related_report_id' => $claim->report->id,
        ]);

        $this->sendEmailNotification($user, $notification, 'Claim Approved', $claim);

        return $notification;
    }

    public function notifyClaimRejected(User $user, $claim)
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'claim_rejected',
            'title' => 'Claim Rejected',
            'message' => "Your claim for \"{$claim->report->title}\" has been rejected.",
            'related_claim_id' => $claim->id,
            'related_report_id' => $claim->report->id,
        ]);

        $this->sendEmailNotification($user, $notification, 'Claim Rejected', $claim);

        return $notification;
    }

    private function sendEmailNotification(User $user, Notification $notification, $subject, $data)
    {
        try {
            // Send email using Laravel's mail system
            Mail::send('emails.notification', [
                'user' => $user,
                'notification' => $notification,
                'data' => $data,
            ], function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject . ' - FYP Lost & Found');
            });

            $notification->update(['is_email_sent' => true]);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification email: ' . $e->getMessage());
        }
    }
}
