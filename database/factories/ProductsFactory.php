<?php

namespace Database\Factories;

use App\Models\Prod_Types;
use App\Models\Products;
use App\Models\Supplier;
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
        // $randomizedPriceCode = $this->faker->randomElement(['O', 'R', 'G', 'R', 'A', 'N', 'I', 'Z', 'E', 'D', 'B'], 3);
            return [
                'prod_type_ID' => Prod_Types::factory(),
                'supplier_ID' => Supplier::factory(),
                'part_num' =>  $this->faker->randomNumber(2),
                'part_name' => $this->faker->randomElement(['Head Gasket Carbon', 'Wheel Cylinder Assy', 'Head Gasket', 'Water Pump', 'Battery Relay', 'Rotor Disc', 'Valve Cover Gasket', 'Brake Shoes']),
                'brand'=>  $this->faker->randomElement(['ANLD', 'GMB', 'F-MANGO1', 'BENDIX', 'VIC', 'POWER PLUS', 'NEW ERA', 'MUGOL']),
                'model'=>  $this->faker->randomElement(['NISSAN NAVARRA 2022', 'SUZUKI', 'J2 2.7 KIA', 'JT 3.0', 'KIA KC700', 'NISSAN CALIBRE', 'F5A', 'JT/ PREGIO 3.0/BONGO']),
                'price_code' => implode('', $this->faker->randomElements(['O', 'R', 'G', 'A', 'N', 'I', 'Z', 'E', 'D', 'B'], 3)), // Extract a Random 3 characters based on the randomizedPriceCode and Convert Array to String
                'stock' =>  $this->faker->randomNumber(3),
            ];

}

 
 }
