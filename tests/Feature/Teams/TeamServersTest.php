<?php

namespace Tests\Feature\Teams;

use App\Server;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamServersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_update_all_servers_available_to_her_team()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->create([
            'user_id' => $user->id
        ]);

        $server = factory(Server::class)->create([
            'user_id' => $user->id
        ]);

        $server2 = factory(Server::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->patchJson("/teams/{$team->id}/servers", [
                'servers' => [$server->id]
            ])
            ->assertStatus(200)
            ->assertJson([
                'id' => $team->id,
                'servers' => [
                    [
                        'id' => $server->id
                    ]
                ]
            ]);

        $this->assertEquals($team->fresh()->servers->count(), 1);

        $this->actingAs($user)
            ->patchJson("/teams/{$team->id}/servers", [
                'servers' => [$server2->id]
            ])
            ->assertStatus(200)
            ->assertJson([
                'id' => $team->id,
                'servers' => [
                    [
                        'id' => $server2->id
                    ]
                ]
            ]);

        $this->assertEquals($team->fresh()->servers->count(), 1);
    }
}
