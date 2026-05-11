<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();

        return view('home', compact('categories'));
    }

    public function show(Category $category): View
    {
        $category->load('masterclasses.instructor', 'masterclasses.registrations.user');

        return view('category.show', compact('category'));
    }
}
