<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;


   /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $order = 1;   

        return [
            'name' => $order.$this->faker->secondaryAddress,
            'code' => $order++."ajose",
            'price' => rand(100, 500),
            'status' => 1,        
        ];
    }
}
