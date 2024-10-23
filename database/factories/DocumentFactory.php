<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        // Generate the title and format first
        $title = $this->faker->words(3, true);
        $format = $this->faker->randomElement(['pdf', 'docx', 'xlsx', 'txt']);
        
        return [
            'title' => $title,
            'format' => $format,
            'path_url' => '/storage/documents/' . $title . $format,
            'category_id' => Category::factory(),
            'user_id' => User::factory()
        ];
    }
}
