<?php

namespace App\Listeners\Subscriptions;

use App\User;
use Illuminate\Support\Facades\Log;

class UpdateUserSubscription
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = User::where('email', $event->email)->first();

        if (! $user) {
            return;
        }

        $user->subscription()->update([
            'status' => $event->status,
            'subscription_plan_id' => $event->subscription_plan_id,
            'subscription_id' => $event->subscription_id,
            'next_bill_date' => isset($event->next_bill_date) ? $event->next_bill_date : null,
        ]);
    }
}
