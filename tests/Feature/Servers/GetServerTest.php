<?php

namespace Tests\Feature\Servers;

use App\User;
use App\Server;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetServerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_get_all_servers_in_the_same_region_and_providers()
    {
        $user = factory(User::class)->create();

        $server1 = factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'nyc1',
            'user_id' => $user->id
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

        // We'll throw in a server that does not belong to this user,
        // just to make sure the results does not include servers
        // that do not belong to the authenticated user
        factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'nyc1'
        ]);

        $response = $this->actingAs($user)->getJson("/servers/{$server1->id}");

        $response->assertJson([
            'family_servers' => [[
                'id' => $server2->id,
                'provider' => DIGITAL_OCEAN,
                'region' => 'nyc1'
            ], [
                'id' => $server3->id,
                'provider' => DIGITAL_OCEAN,
                'region' => 'nyc1'
            ]]
        ]);

        $this->assertEquals(
            count(
                json_decode($response->getContent())->family_servers
            ),
            2
        );
    }
}
