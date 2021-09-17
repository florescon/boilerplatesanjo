<?php

namespace Database\Factories;

use App\Models\Departament;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartamentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Departament::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $departament = 1;   

        return [
            'name' => $this->faker->name,
            'email' => $departament++.$this->faker->email,
            'comment' => $this->faker->realText(rand(10, 30)),
        ];
    }
}
