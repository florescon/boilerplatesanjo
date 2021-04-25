<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class MaterialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Material::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'part_number' => Uuid::uuid4()->toString(),
            'name' => $this->faker->word,
            'description' => $this->faker->jobTitle,
            'acquisition_cost' => rand(100, 500),
            'price' => rand(100, 500),
            'stock' => rand(5, 100),
            'unit_id' => rand(1, 100),
            'color_id' => rand(1, 100),
            'size_id' => rand(1, 40),
        ];
    }
}
