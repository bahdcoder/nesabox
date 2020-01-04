<?php

namespace Tests\Feature\Servers;

use App\User;
use App\Server;
use Tests\TestCase;
use App\Jobs\Servers\Initialize;
use App\Subscription;
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

        $response
            ->assertStatus(200)
            ->assertSeeText('apt-get install -y nginx')
            ->assertDontSeeText('mongodb')
            ->assertDontSeeText('mysql')
            ->assertDontSeeText('mariadb');
    }

    public function test_a_user_cannot_create_more_than_one_server_without_at_least_a_pro_plan()
    {
        Queue::fake();

        $user = factory(User::class)->create();

        factory(Server::class)->create([
            'provider' => DIGITAL_OCEAN,
            'region' => 'nyc1',
            'user_id' => $user->id,
            'type' => 'load_balancer'
        ]);

        $this->actingAs($user)->postJson('/servers')->assertStatus(400)->assertJson([
            'message' => 'Please upgrade your plan to add more servers.'
        ]);
    }

    public function test_a_user_on_a_pro_or_business_plan_can_create_more_than_one_server()
    {
        Queue::fake();

        $finn = factory(User::class)->create();
        $jake = factory(User::class)->create();

        factory(Subscription::class)->create([
            'status' => 'active',
            'user_id' => $finn->id,
            'subscription_plan_id' => config('paddle.plans')->get('pro')
        ]);

        factory(Subscription::class)->create([
            'status' => 'active',
            'user_id' => $jake->id,
            'subscription_plan_id' => config('paddle.plans')->get('business')
        ]);

        factory(Server::class)->create([
            'user_id' => $finn->id,
        ]);

        factory(Server::class)->create([
            'user_id' => $jake->id,
        ]);

        $this->actingAs($jake)->postJson('/servers')->assertStatus(422);
        $this->actingAs($finn)->postJson('/servers')->assertStatus(422);
    }
}
