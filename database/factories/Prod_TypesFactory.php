<?php

namespace Database\Factories;

use App\Models\Prod_Types;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prod_Types>
 */
class Prod_TypesFactory extends Factory
{
    protected $model = Prod_Types::class;

    public function definition()
    {  
       
            return [
                'product_type_name' => $this->faker->randomElement(['Main Bearing', 'Wheel Hub', 'Steering', 'Engine', 'Spare Tire Covers', 'Car Ornaments']),
                // ... other attributes
            ];
      
    }
}
