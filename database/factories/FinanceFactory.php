<?php

namespace Database\Factories;

use App\Models\Finance;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Finance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->realText(rand(10, 25)),
            'user_id' => rand(1, 2),
            'amount' => rand(10, 1000),
            'comment' => $this->faker->realText(rand(10, 305)),
            'ticket_text' => $this->faker->realText(rand(50, 100)),
            'type' => $this->faker->randomElement(['income', 'expense']),
            'payment_method_id' =>  rand(0, 7),
            'audi_id' => rand(1, 2),
        ];
    }
}
