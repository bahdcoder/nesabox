<?php

namespace Tests\Feature\Servers;

use App\User;
use App\Server;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Fakes\Process;

class UpdateBalancedServersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_cannot_update_balanced_servers_on_servers_that_are_not_load_balancers()
    {
        $user = factory(User::class)->create();

        $this->withExceptionHandling();

        $server = factory(Server::class)->create([
            'type' => 'default',
            'user_id' => $user->id
        ]);

        $server2 = factory(Server::class)->create();

        $site = $server->sites()->create([
            'name' => 'test.nesabox.com'
        ]);

        $this->actingAs($user)
            ->patchJson("servers/{$server->id}/sites/{$site->id}/upstream", [
                'servers' => [$server2->id]
            ])
            ->assertStatus(403)
            ->assertJson([
                'message' => 'The server must be a load balancer.'
            ]);
    }

    public function test_a_user_cannot_update_a_balanced_server_that_does_not_belong_to_them()
    {
        $user = factory(User::class)->create();

        $this->withExceptionHandling();

        $server = factory(Server::class)->create([
            'type' => 'load_balancer'
        ]);

        $server2 = factory(Server::class)->create();

        $site = $server->sites()->create([
            'name' => 'test.nesabox.com'
        ]);

        $this->actingAs($user)
            ->patchJson("servers/{$server->id}/sites/{$site->id}/upstream", [
                'servers' => [$server2->id]
            ])
            ->assertStatus(403)
            ->assertJson([
                'message' => 'This action is unauthorized.'
            ]);
    }

    public function test_a_user_can_update_balanced_servers_on_a_load_balancer()
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

        $site = $server1->sites()->create([
            'name' => 'test.nesabox.com'
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
            ->patchJson("servers/{$server1->id}/sites/{$site->id}/upstream", [
                'servers' => [$server2->id, $server3->id]
            ])
            ->assertStatus(200)
            ->assertJson([
                'id' => $server1->id,
                'balanced_servers' => [
                    [
                        'balanced_server_id' => $server2->id,
                        'server_id' => $server1->id
                    ],
                    [
                        'balanced_server_id' => $server3->id,
                        'server_id' => $server1->id
                    ]
                ]
            ]);
    }

    public function test_a_server_not_found_error_is_thrown_if_user_is_trying_to_balance_server_in_a_different_region()
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

        $site = $server1->sites()->create([
            'name' => 'test.nesabox.com'
        ]);

        $server1->sshkeys()->create([
            'is_app_key' => true,
            'name' => $this->faker->name,
            'status' => 'active'
        ]);

        $server2 = factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'singapore',
            'user_id' => $user->id
        ]);

        $server3 = factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'nyc1',
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->patchJson("servers/{$server1->id}/sites/{$site->id}/upstream", [
                'servers' => [$server2->id, $server3->id]
            ])
            ->assertStatus(404);
    }
}
