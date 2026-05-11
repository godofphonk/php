<?php

namespace Tests\Feature;

use App\Models\Masterclass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_for_available_masterclass(): void
    {
        $user = User::factory()->create();
        $masterclass = Masterclass::factory()->create(['max_participants' => 10]);

        $response = $this->actingAs($user)->post("/registration/{$masterclass->id}", []);

        $response->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'masterclass_id' => $masterclass->id,
        ]);
    }

    public function test_registration_confirmation_page_loads(): void
    {
        $user = User::factory()->create();
        $masterclass = Masterclass::factory()->create();

        $response = $this->actingAs($user)->get("/registration/{$masterclass->id}/create");

        $response->assertStatus(200);
    }

    public function test_masterclass_has_registrations_relationship(): void
    {
        $masterclass = Masterclass::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Registration::factory()->create([
            'masterclass_id' => $masterclass->id,
            'user_id' => $user1->id,
        ]);
        Registration::factory()->create([
            'masterclass_id' => $masterclass->id,
            'user_id' => $user2->id,
        ]);

        $this->assertCount(2, $masterclass->registrations);
    }
}
