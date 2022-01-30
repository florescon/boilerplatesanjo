<?php

namespace Database\Factories;

use App\Models\ProductOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => 10,
            'product_id' => 4338,
            'quantity' => rand(1, 10),
            'price' => rand(20, 50),
            'type' => rand(1, 2),
        ];
    }
}
