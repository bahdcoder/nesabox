<?php

namespace App\Traits;

use App\Subscription;

trait HasSubscription
{
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     *
     * This method checks if the user is subscribed to a plan
     */
    public function subscribed($plan = null)
    {
        if (!$plan) {
            return $this->subscription
                ? $this->subscription->status === 'active'
                : false;
        }

        if (!$this->subscription) {
            return false;
        }

        return $this->subscription->subscription_plan_id ===
            config('paddle.plans')->get($plan) &&
            $this->subscription->status === 'active';
    }

    public function subscribedToBusiness()
    {
        return $this->subscribed('business');
    }

    public function subscribedToPro()
    {
        return $this->subscribed('pro');
    }
}
