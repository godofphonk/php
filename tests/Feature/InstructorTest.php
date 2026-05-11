<?php

namespace Tests\Feature;

use App\Models\Masterclass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_view_dashboard(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);

        $response = $this->actingAs($instructor)->get('/instructor/dashboard');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_instructor_dashboard(): void
    {
        $response = $this->get('/instructor/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_instructor_sees_their_masterclasses(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $masterclass = Masterclass::factory()->create([
            'instructor_id' => $instructor->id,
            'title' => 'My Masterclass',
        ]);

        $response = $this->actingAs($instructor)->get('/instructor/dashboard');

        $response->assertStatus(200);
        $response->assertSee('My Masterclass');
    }
}
