<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Архитектурное моделирование',
                'description' => 'Архитектурное моделирование — это изготовление моделей зданий, сооружений, исторических памятников, а также инженерных и фортификационных сооружений.',
            ],
            [
                'name' => 'Кулинария',
                'description' => 'Мастер-классы по кулинарии научат вас готовить вкусные и красивые блюда различных кухонь мира.',
            ],
            [
                'name' => 'Резьба по дереву',
                'description' => 'Резьба по дереву — один из видов декоративно-прикладного искусства, искусство создания изображений на деревянных изделиях.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
