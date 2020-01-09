<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelPaddle\Paddle;

class SubscriptionController extends Controller
{
    /**
     *
     * Switch a user's subscription plan
     *
     *
     */
    public function update()
    {
        $request = request();
        $user = auth()->user();
        $plan = config('paddle.plans')->get($request->plan);

        $businessPlanId = config('paddle.plans')->get('business');

        // if an invalid plan is passed, throw an error
        if (!in_array($request->plan, ['pro', 'business'])) {
            abort(400, 'Invalid plan selected.');
        }

        // if user is not subscribed throw an error
        if (!$user->subscribed()) {
            abort(400, 'A subscription is required.');
        }

        // if user is changing to a plan they are already on, throw an error
        if ($user->subscription->subscription_plan_id === $plan) {
            abort(400, 'You are already on this plan.');
        }

        // if a user is downgrading, check the number of servers
        if (
            $user->subscription->subscription_plan_id === $businessPlanId &&
            $user->servers()->count() > 1
        ) {
            abort(400, 'You have too many servers to downgrade to this plan.');
        }

        // if user is upgrading or downgrading and all is well, go on
        $subscription = Paddle::subscription()
            ->updateUser([
                'subscription_id' => $user->subscription->subscription_id,
                'plan_id' => $plan
            ])
            ->send();

        $user->subscription()->update([
            'subscription_plan_id' => $plan,
            'next_bill_date' => $subscription['next_payment']['date']
        ]);

        return new UserResource($user->fresh());
    }

    /**
     *
     * Cancel a user's subscription
     */
    public function destroy()
    {
        $user = auth()->user();

        // if user is not subscribed throw an error
        if (!$user->subscribed()) {
            abort(400, 'A subscription is required.');
        }
        // if user is upgrading or downgrading and all is well, go on
        Paddle::subscription()
            ->cancelUser([
                'subscription_id' => $user->subscription->subscription_id
            ])
            ->send();

        $user->subscription()->delete();

        return new UserResource($user->fresh());
    }
}
