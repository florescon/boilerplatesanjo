<?php

namespace Database\Factories;

use App\Models\ModelProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModelProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModelProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->userName,
        ];
    }
}
