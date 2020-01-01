<?php

namespace Tests\Feature\Servers;

use App\Jobs\Servers\Initialize;
use App\Server;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateServerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_create_a_custom_server()
    {
        Queue::fake();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/servers', [
            'provider' => 'custom',
            'name' => $this->faker->slug,
            'ip_address' => $this->faker->ipv4,
            'databases' => ['mysql'],
            'size' => '512mb'
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'status' => 'initializing',
                'databases' => ['mysql'],
                'firewall_rules' => [
                    [
                        'name' => 'SSH',
                        'port' => 22
                    ],
                    [
                        'name' => 'HTTP',
                        'port' => 80
                    ],
                    [
                        'name' => 'HTTPS',
                        'port' => 443
                    ]
                ]
            ])
            ->assertJsonStructure([
                'deploy_command',
                'deploy_script',
                'ip_address'
            ]);
            
        $server = Server::first();

        Queue::assertPushed(Initialize::class, function ($job) use ($server) {
            return $job->server->id === $server->id;
        });
    }
}
