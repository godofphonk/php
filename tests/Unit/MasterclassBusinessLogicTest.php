<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Masterclass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MasterclassBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_masterclass_has_no_available_spots_when_full(): void
    {
        $masterclass = Masterclass::factory()->create(['max_participants' => 2]);
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

        $masterclass->refresh();
        $this->assertEquals(0, $masterclass->available_spots);
        $this->assertFalse($masterclass->hasAvailableSpots());
    }

    public function test_masterclass_casts_date_correctly(): void
    {
        $masterclass = Masterclass::factory()->create([
            'date' => '2026-12-25',
        ]);

        $this->assertInstanceOf(Carbon::class, $masterclass->date);
    }

    public function test_masterclass_casts_price_correctly(): void
    {
        $masterclass = Masterclass::factory()->create([
            'price' => 99.99,
        ]);

        $this->assertEquals('99.99', $masterclass->price);
    }

    public function test_category_has_many_masterclasses(): void
    {
        $category = Category::factory()->create();
        Masterclass::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->masterclasses);
    }

    public function test_user_has_many_masterclasses_as_instructor(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Masterclass::factory()->count(2)->create(['instructor_id' => $instructor->id]);

        $this->assertCount(2, $instructor->masterclasses);
    }

    public function test_user_has_many_registrations(): void
    {
        $user = User::factory()->create();
        $masterclass1 = Masterclass::factory()->create();
        $masterclass2 = Masterclass::factory()->create();

        Registration::factory()->create([
            'user_id' => $user->id,
            'masterclass_id' => $masterclass1->id,
        ]);
        Registration::factory()->create([
            'user_id' => $user->id,
            'masterclass_id' => $masterclass2->id,
        ]);

        $this->assertCount(2, $user->registrations);
    }
}
