<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterclassTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_create_masterclass(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);

        $response = $this->actingAs($instructor)->get('/masterclass/create');

        $response->assertStatus(200);
    }

    public function test_visitor_cannot_create_masterclass(): void
    {
        $visitor = User::factory()->create(['role' => 'visitor']);

        $response = $this->actingAs($visitor)->get('/masterclass/create');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_create_masterclass(): void
    {
        $response = $this->get('/masterclass/create');

        $response->assertRedirect('/login');
    }
}
