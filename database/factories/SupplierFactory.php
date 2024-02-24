<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    
    public function definition(): array
    {
        return [

            'supplier_name' => $this->faker->name(), 
            
        ];
    }
}
