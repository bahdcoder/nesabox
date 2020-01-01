<?php

namespace Tests\Feature\Servers;

use App\User;
use App\Server;
use Tests\TestCase;
use App\Jobs\Servers\Initialize;
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

    public function test_a_user_can_create_custom_load_balancer_server()
    {
        Queue::fake();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/servers', [
            'provider' => 'custom',
            'name' => $this->faker->slug,
            'ip_address' => $this->faker->ipv4,
            'databases' => ['mysql'],
            'size' => '512mb',
            'type' => 'load_balancer'
        ]);

        $response->assertStatus(201)->assertJson([
            'type' => 'load_balancer'
        ]);

        $server = Server::first();

        $content = json_decode($response->getContent());

        $this->assertEquals($server->id, $content->id);
        $this->assertEquals($server->status, 'initializing');

        Queue::assertPushed(Initialize::class, function ($job) use ($server) {
            return $job->server->id === $server->id;
        });
    }

    public function test_after_server_creation_an_init_script_can_be_fetched_depending_on_server_type()
    {
        $server = factory(Server::class)->create([
            'type' => 'load_balancer',
            'databases' => ['mysql', 'mariadb', 'mongodb']
        ]);

        $server->sshkeys()->create([
            'is_app_key' => true,
            'name' => $this->faker->name,
            'status' => 'active'
        ]);

        $response = $this->getJson(
            "/servers/{$server->id}/vps?api_token=" . $server->user->api_token
        );

        $response->assertStatus(200)
            ->assertSeeText('apt-get install -y nginx')
            ->assertDontSeeText('mongodb')
            ->assertDontSeeText('mysql')
            ->assertDontSeeText('mariadb');
    }
}
