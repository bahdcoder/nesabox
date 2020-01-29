<?php

namespace Tests\Feature\Teams;

use App\Team;
use App\User;
use Tests\TestCase;
use App\Mail\InviteToTeam;
use App\Subscription;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamInvitesTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_a_user_can_invite_another_registered_user_to_a_team()
    {
        Mail::fake();

        $jane = factory(User::class)->create();

        factory(Subscription::class)->create([
            'status' => 'active',
            'user_id' => $jane->id,
            'subscription_plan_id' => config('paddle.plans')->get('business')
        ]);

        $mike = factory(User::class)->create();

        $team = factory(Team::class)->create([
            'user_id' => $jane->id
        ]);

        $this->actingAs($jane)
            ->postJson("/teams/{$team->id}/invites", [
                'email' => $mike->email
            ])
            ->assertStatus(200)
            ->assertJson([
                'user_id' => $jane->id,
                'invites' => [[
                    'team_id' => $team->id
                ]]
            ]);

        Mail::assertQueued(InviteToTeam::class, function ($mail) use ($mike) {
            return $mail->hasTo($mike->email);
        });
    }

    public function test_a_user_can_accept_or_decline_an_invite()
    {
        $jane = factory(User::class)->create();
        $finn = factory(User::class)->create();
        $mike = factory(User::class)->create();

        factory(Subscription::class)->create([
            'status' => 'active',
            'user_id' => $jane->id,
            'subscription_plan_id' => config('paddle.plans')->get('business')
        ]);

        factory(Subscription::class)->create([
            'status' => 'active',
            'user_id' => $finn->id,
            'subscription_plan_id' => config('paddle.plans')->get('business')
        ]);

        $team = factory(Team::class)->create([
            'user_id' => $jane->id
        ]);

        $circle = factory(Team::class)->create([
            'user_id' => $finn->id
        ]);

        $this->actingAs($jane)->postJson("/teams/{$team->id}/invites", [
            'email' => $mike->email
        ]);

        $this->actingAs($finn)->postJson("/teams/{$circle->id}/invites", [
            'email' => $mike->email
        ]);

        $invite = $mike->memberships->get(0);
        $invite2 = $mike->memberships->get(1);

        $this->actingAs($mike)
            ->patchJson("/invites/{$invite->id}/accepted")
            ->assertStatus(200);
        $this->actingAs($mike)
            ->patchJson("/invites/{$invite2->id}/declined")
            ->assertStatus(200);

        $this->actingAs($mike)
            ->getJson('/teams/memberships')
            ->assertStatus(200)
            ->assertJson([]);

        $this->assertNull($invite2->fresh());
        $this->assertEquals($invite->fresh()->status, 'accepted');
    }
}
