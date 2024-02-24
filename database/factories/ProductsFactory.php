<?php

namespace Database\Factories;

use App\Models\Prod_Types;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;
/** @var \Illuminate\Database\Eloquent\Factory $factory */
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    protected $model = Products::class;
    public function definition()
    {
            return [
                'prod_type_ID' => Prod_Types::factory(),
                'part_num' =>  $this->faker->randomNumber(2),
                'part_name' =>  $this->faker->name(),
                'brand'=>  $this->faker->words(1, true), 
                'model'=>  $this->faker->words(1, true), 
                'price_code' =>  $this->faker->numerify('ABC###'),
            ];

}
 }
