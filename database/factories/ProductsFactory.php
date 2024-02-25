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
                'part_name' => $this->faker->randomElement(['Head Gasket Carbon', 'Wheel Cylinder Assy', 'Head Gasket', 'Water Pump', 'Battery Relay', 'Rotor Disc', 'Valve Cover Gasket', 'Brake Shoes']),
                'brand'=>  $this->faker->randomElement(['ANLD', 'GMB', 'F-MANGO1', 'BENDIX', 'VIC', 'POWER PLUS', 'NEW ERA', 'MUGOL']),
                'model'=>  $this->faker->randomElement(['NISSAN NAVARRA 2022', 'SUZUKI', 'J2 2.7 KIA', 'JT 3.0', 'KIA KC700', 'NISSAN CALIBRE', 'F5A', 'JT/ PREGIO 3.0/BONGO']),
                'price_code' =>  $this->faker->numerify('ABC###'),
            ];

}

 
 }
