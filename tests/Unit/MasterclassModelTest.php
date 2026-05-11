<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Masterclass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterclassModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_masterclass_has_fillable_attributes(): void
    {
        $masterclass = new Masterclass;

        $expected = [
            'category_id',
            'instructor_id',
            'title',
            'description',
            'date',
            'time',
            'max_participants',
            'price',
        ];

        $this->assertEquals($expected, $masterclass->getFillable());
    }

    public function test_masterclass_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $masterclass = Masterclass::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $masterclass->category);
        $this->assertEquals($category->id, $masterclass->category->id);
    }

    public function test_masterclass_belongs_to_instructor(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $masterclass = Masterclass::factory()->create(['instructor_id' => $instructor->id]);

        $this->assertInstanceOf(User::class, $masterclass->instructor);
        $this->assertEquals($instructor->id, $masterclass->instructor->id);
    }

    public function test_masterclass_has_available_spots(): void
    {
        $masterclass = Masterclass::factory()->create(['max_participants' => 10]);

        $this->assertEquals(10, $masterclass->available_spots);
        $this->assertTrue($masterclass->hasAvailableSpots());
    }

    public function test_masterclass_available_spots_decrease_with_registrations(): void
    {
        $masterclass = Masterclass::factory()->create(['max_participants' => 10]);
        $user = User::factory()->create();

        Registration::factory()->create([
            'masterclass_id' => $masterclass->id,
            'user_id' => $user->id,
        ]);

        $masterclass->refresh();
        $this->assertEquals(9, $masterclass->available_spots);
    }
}
