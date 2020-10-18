<?php

namespace Tests\Feature\Servers;

use App\User;
use App\Team;
use App\Server;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetServersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_get_all_their_servers_and_teams_servers()
    {
        // jake creates a server, creates a team, adds ryan to the team and shares the server with that team.
        // meaning ryan now has access to jake's server.

        // ryan also creates a server but does not share it with anyone.
        $jake = factory(User::class)->create();
        $ryan = factory(User::class)->create();
        $susan = factory(User::class)->create();

        $team = factory(Team::class)->create([
            'user_id' => $jake->id
        ]);

        $circle = factory(Team::class)->create([
            'user_id' => $susan->id
        ]);

        $team
            ->invites()
            ->create([
                'user_id' => $ryan->id,
                'email' => $ryan->email
            ])
            ->update([
                'status' => 'accepted'
            ]);

        $circle
            ->invites()
            ->create([
                'user_id' => $ryan->id,
                'email' => $ryan->email
            ])
            ->update([
                'status' => 'accepted'
            ]);

        $this->assertTrue($team->hasMember($jake));
        $this->assertTrue($circle->hasMember($ryan));

        $server = factory(Server::class)->create([
            'user_id' => $jake->id
        ]);

        $susansServer = factory(Server::class)->create([
            'user_id' => $susan->id
        ]);

        $server->sshkeys()->create([
            'is_app_key' => true,
            'name' => $this->faker->name
        ]);

        $susansServer->sshkeys()->create([
            'is_app_key' => true,
            'name' => $this->faker->name
        ]);

        $server2 = factory(Server::class)->create([
            'user_id' => $ryan->id
        ]);

        $server2->sshkeys()->create([
            'is_app_key' => true,
            'name' => $this->faker->name
        ]);

        $team->servers()->sync([$server->id]);

        $circle->servers()->sync([$susansServer->id]);

        $response = $this->actingAs($ryan)
            ->getJson('/servers')
            ->assertStatus(200)
            ->assertJson([
                'servers' => [
                    [
                        'id' => $server2->id
                    ]
                ],
                'team_servers' => [
                    [
                        'team' => [
                            'id' => $team->id,
                            'servers' => [
                                [
                                    'id' => $server->id
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(
            count(json_decode($response->getContent())->team_servers),
            2
        );

        $this->assertEquals(
            count(
                json_decode($response->getContent())->team_servers[0]->team
                    ->servers
            ),
            1
        );

        $response = $this->actingAs($jake)
            ->getJson('/servers')
            ->assertStatus(200)
            ->assertJson([
                'servers' => [
                    [
                        'id' => $server->id
                    ]
                ],
                'team_servers' => []
            ]);

        $this->assertEquals(
            count(json_decode($response->getContent())->team_servers),
            0
        );
    }
}
