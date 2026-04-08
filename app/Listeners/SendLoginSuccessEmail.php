<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendLoginSuccessEmail
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (! $user || $user->is_blocked || empty($user->email)) {
            return;
        }

        if (! request()->hasSession()) {
            return;
        }

        $sessionKey = 'welcome_email_sent_on_login_' . $user->id;

        if (request()->session()->has($sessionKey)) {
            return;
        }

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
            request()->session()->put($sessionKey, true);
        } catch (Throwable $e) {
            Log::error('Failed to send welcome email on login.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
