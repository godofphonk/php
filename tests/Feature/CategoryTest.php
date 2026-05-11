<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_index_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_category_show_page_loads(): void
    {
        $category = Category::factory()->create();

        $response = $this->get("/category/{$category->id}");

        $response->assertStatus(200);
    }

    public function test_categories_are_displayed_on_index(): void
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
