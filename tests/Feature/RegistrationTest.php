<?php

namespace Tests\Feature;

use App\Models\Masterclass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_registration_form(): void
    {
        $user = User::factory()->create(['role' => 'visitor']);
        $masterclass = Masterclass::factory()->create(['max_participants' => 10]);

        $response = $this->actingAs($user)->get("/registration/{$masterclass->id}/create");

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_registration_form(): void
    {
        $masterclass = Masterclass::factory()->create();

        $response = $this->get("/registration/{$masterclass->id}/create");

        $response->assertRedirect('/login');
    }

    public function test_registration_belongs_to_user_and_masterclass(): void
    {
        $user = User::factory()->create();
        $masterclass = Masterclass::factory()->create();

        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'masterclass_id' => $masterclass->id,
        ]);

        $this->assertEquals($user->id, $registration->user->id);
        $this->assertEquals($masterclass->id, $registration->masterclass->id);
    }
}
