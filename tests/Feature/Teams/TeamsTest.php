<?php

namespace Tests\Feature\Teams;

use App\Team;
use App\TeamInvite;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_fetch_all_her_teams()
    {
        $user = factory(User::class)->create();

        $teams = factory(Team::class, 17)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/teams')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'name' => $teams[0]->name
                    ]
                ]
            ]);
    }

    public function test_a_user_can_fetch_all_her_team_memberships()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $team = factory(Team::class)->create([
            'user_id' => $user->id
        ]);

        $team2 = factory(Team::class)->create([
            'user_id' => $user2->id
        ]);

        $this->actingAs($user)->postJson("/teams/{$team->id}/invites", [
            'email' => $user3->email
        ]);

        $this->actingAs($user2)->postJson("/teams/{$team2->id}/invites", [
            'email' => $user3->email
        ]);

        $this->actingAs($user3)
            ->getJson('/teams/memberships')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'team_id' => $team->id
                    ],
                    [
                        'team_id' => $team2->id
                    ]
                ]
            ]);
    }

    public function test_a_user_can_create_a_team()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postJson('/teams', [
                'name' => $this->faker->name
            ])
            ->assertStatus(200)
            ->assertJson([
                'user_id' => $user->id
            ]);
    }

    public function test_team_name_must_be_unique_to_user()
    {
        $user = factory(User::class)->create();

        $name = $this->faker->name;

        factory(Team::class)->create([
            'name' => $name,
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->postJson('/teams', [
                'name' => $name
            ])
            ->assertStatus(422);

        $user2 = factory(User::class)->create();

        $this->actingAs($user2)
            ->postJson('/teams', [
                'name' => $name
            ])
            ->assertStatus(200);
    }

    public function test_a_user_cannot_update_a_team_that_does_not_belong_to_them()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $name = $this->faker->name;

        $team = factory(Team::class)->create([
            'name' => $name,
            'user_id' => $user->id
        ]);

        $this->actingAs($user2)
            ->patchJson("/teams/{$team->id}", [
                'name' => $this->faker->name
            ])
            ->assertStatus(403);

        $this->assertEquals($team->fresh()->name, $name);
    }

    public function test_a_user_can_delete_their_team()
    {
        $user = factory(User::class)->create();

        $team = factory(Team::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->deleteJson("/teams/{$team->id}")
            ->assertStatus(200);
    }

    public function test_a_user_cannot_delete_a_team_they_do_not_own()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $team = factory(Team::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user2)
            ->deleteJson("/teams/{$team->id}")
            ->assertStatus(403);
    }
}
