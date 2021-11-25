<?php

namespace Database\Factories;

use App\Models\Cash;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cash::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(rand(10, 25)),
            'comment' => $this->faker->realText(rand(10, 305)),
            'initial' => rand(10, 1000),
            'total' => rand(2000, 3000),
            'audi_id' => rand(1, 2),
            'checked' => now(),
        ];
    }
}
