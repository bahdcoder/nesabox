<?php

namespace App\Listeners\Subscriptions;

use App\User;
use Illuminate\Support\Facades\Log;

class SubscribeUserToPlan
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
            // TODO: Notify us someone weirdly paid without a nesabox account
            return;
        };

        $user->subscription()->create([
            'status' => $event->status,
            'subscription_plan_id' => $event->subscription_plan_id,
            'subscription_id' => $event->subscription_id,
            'next_bill_date' => $event->next_bill_date,
        ]);
    }
}
