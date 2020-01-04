<?php

namespace Tests\Feature\Teams;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTeamTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_create_a_team()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->postJson('/teams', [
            'name' => $this->faker->name
        ])->assertStatus(200)->assertJson([
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

        $this->actingAs($user)->postJson('/teams', [
            'name' => $name
        ])->assertStatus(422);

        $user2 = factory(User::class)->create();

        $this->actingAs($user2)->postJson('/teams', [
            'name' => $name
        ])->assertStatus(200);
    }
}
