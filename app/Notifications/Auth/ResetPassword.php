<?php

namespace App\Notifications\Auth;

use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as NotificationsResetPassword;
use Illuminate\Support\Facades\Log;

class ResetPassword extends NotificationsResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        Log::info($this->token);

        return (new MailMessage())
            ->subject(Lang::get('Reset Password Notification'))
            ->line(
                Lang::get(
                    'You are receiving this email because we received a password reset request for your account.'
                )
            )
            ->action(
                Lang::get('Reset Password'),
                url(
                    config('app.client_url') .
                        '/auth/reset-password/' .
                        $this->token
                )
            )
            ->line(
                Lang::get(
                    'This password reset link will expire in :count minutes.',
                    [
                        'count' => config(
                            'auth.passwords.' .
                                config('auth.defaults.passwords') .
                                '.expire'
                        )
                    ]
                )
            )
            ->line(
                Lang::get(
                    'If you did not request a password reset, no further action is required.'
                )
            );
    }
}
