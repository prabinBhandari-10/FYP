<?php

namespace App\Listeners;

use App\Notifications\LoginThankYouNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendLoginSuccessEmail
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (! $user || ! $user->email_verified_at || $user->is_blocked) {
            return;
        }

        if (! request()->hasSession()) {
            return;
        }

        $sessionKey = 'login_thank_you_email_sent_' . $user->id;

        if (request()->session()->has($sessionKey)) {
            return;
        }

        try {
            $user->notify(new LoginThankYouNotification());
            request()->session()->put($sessionKey, true);
        } catch (Throwable $e) {
            Log::error('Failed to send login thank-you email.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
