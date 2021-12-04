<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'file_emb' => $this->faker->mimeType('application/octet-stream'),
            'file_dst' => $this->faker->mimeType('application/vnd.ms-office'),
            'comment' => $this->faker->realText(rand(10, 30)),
        ];
    }
}
