<?php

namespace Tests\Feature\Network;

use App\User;
use App\Server;
use Tests\TestCase;
use Tests\Fakes\Process;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateServerNetworkTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_add_family_servers_to_a_server_network()
    {
        $user = factory(User::class)->create();

        // Swap out the real process runner with a fake one.
        app()->bind('ProcessRunner', function ($command) {
            return new Process($command);
        });

        $server1 = factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'nyc1',
            'user_id' => $user->id,
            'type' => 'load_balancer'
        ]);

        $server1->sshkeys()->create([
            'is_app_key' => true,
            'name' => $this->faker->name,
            'status' => 'active'
        ]);

        $server2 = factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'nyc1',
            'user_id' => $user->id
        ]);

        $server3 = factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'nyc1',
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->patchJson("servers/{$server1->id}/network", [
                'servers' => [$server2->id]
            ])
            ->assertStatus(200)
            ->assertJson([
                'id' => $server1->id,
                'friend_servers' => [[
                    'friend_server_id' => $server2->id,
                    'server_id' => $server1->id
                ]]
            ]);

        $this->actingAs($user)
            ->patchJson("servers/{$server1->id}/network", [
                'servers' => [$server3->id]
            ])
            ->assertStatus(200)
            ->assertJson([
                'id' => $server1->id,
                'friend_servers' => [[
                    'friend_server_id' => $server3->id,
                    'server_id' => $server1->id
                ]]
            ]);
    }
}
