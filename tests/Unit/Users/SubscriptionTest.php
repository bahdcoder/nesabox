<?php

namespace Tests\Unit\Users;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_can_check_if_a_user_subscribed_to_a_plan()
    {
        $mark = factory(User::class)->create();
        $jane = factory(User::class)->create();

        $luke = factory(User::class)->create();

        $mark->subscription()->create([
            'status' => 'active',
            'subscription_plan_id' => config('paddle.plans')->get('pro'),
            'subscription_id' => $this->faker->randomNumber(),
            'next_bill_date' => '2020/04/04'
        ]);

        $luke->subscription()->create([
            'status' => 'deleted',
            'subscription_plan_id' => config('paddle.plans')->get('business'),
            'subscription_id' => $this->faker->randomNumber(),
            'next_bill_date' => '2020/04/04'
        ]);

        $this->assertTrue($mark->subscribed());

        $this->assertTrue($mark->subscribed('pro'));

        $this->assertFalse($mark->subscribed('business'));

        $this->assertFalse($mark->subscribedToBusiness());

        $this->assertTrue($mark->subscribedToPro());

        // for Jane
        $this->assertFalse($jane->subscribed());

        $this->assertFalse($jane->subscribed('pro'));

        $this->assertFalse($jane->subscribed('business'));

        $this->assertFalse($jane->subscribedToBusiness());

        $this->assertFalse($jane->subscribedToPro());

        // for Luke
        $this->assertFalse($luke->subscribed());

        $this->assertFalse($luke->subscribed('pro'));

        $this->assertFalse($luke->subscribed('business'));

        $this->assertFalse($luke->subscribedToBusiness());

        $this->assertFalse($luke->subscribedToPro());
    }
}
