<?php

namespace App\Providers;

use App\Events\UserInvitedToTeam;
use App\Listeners\SendTeamInviteMail;
use Illuminate\Auth\Events\Registered;
use App\Listeners\Subscriptions\SubscribeUserToPlan;
use App\Listeners\Subscriptions\UpdateUserSubscription;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionUpdated;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCancelled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        UserInvitedToTeam::class => [SendTeamInviteMail::class],
        SubscriptionCreated::class => [SubscribeUserToPlan::class],
        SubscriptionUpdated::class => [UpdateUserSubscription::class],
        SubscriptionCancelled::class => [UpdateUserSubscription::class]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
