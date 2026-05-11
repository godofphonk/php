<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_fillable_attributes(): void
    {
        $category = new Category;

        $this->assertEquals(
            ['name', 'description'],
            $category->getFillable()
        );
    }

    public function test_category_can_be_created(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);
    }
}
