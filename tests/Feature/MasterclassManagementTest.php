<?php

namespace Tests\Feature;

use App\Models\Masterclass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterclassManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_access_create_masterclass_form(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);

        $response = $this->actingAs($instructor)->get('/masterclass/create');

        $response->assertStatus(200);
    }

    public function test_instructor_can_edit_their_masterclass(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $masterclass = Masterclass::factory()->create(['instructor_id' => $instructor->id]);

        $response = $this->actingAs($instructor)->get("/masterclass/{$masterclass->id}/edit");

        $response->assertStatus(200);
    }

    public function test_instructor_can_update_their_masterclass(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $masterclass = Masterclass::factory()->create([
            'instructor_id' => $instructor->id,
            'title' => 'Old Title',
        ]);

        $response = $this->actingAs($instructor)->put("/masterclass/{$masterclass->id}", [
            'category_id' => $masterclass->category_id,
            'title' => 'Updated Title',
            'description' => $masterclass->description,
            'date' => $masterclass->date->format('Y-m-d'),
            'time' => '14:00',
            'max_participants' => $masterclass->max_participants,
            'price' => $masterclass->price,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('masterclasses', [
            'id' => $masterclass->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_visitor_cannot_edit_masterclass(): void
    {
        $visitor = User::factory()->create(['role' => 'visitor']);
        $masterclass = Masterclass::factory()->create();

        $response = $this->actingAs($visitor)->get("/masterclass/{$masterclass->id}/edit");

        $response->assertStatus(403);
    }
}
