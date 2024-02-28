<?php

namespace Database\Factories;

use App\Models\Credit_Info;
use App\Providers\currencyformatterprovider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Credit_Info>
 */
class Credit_InfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Credit_Info::class;

    
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new currencyformatterprovider($faker));

        return [
            'debit_date' => $this->faker->dateTime(),
            'invoice_number' => '#' . $this->faker->unique()->numberBetween(10000, 99999),
            'charge' => $faker->currencyFormat,
            'credit_limit' => $faker->currencyFormat,
            'dp_date' => $faker->dateTime(),
            'downpayment' => $faker->currencyFormat,
            'balance' => $faker->currencyFormat,
            'status' => $this->faker->randomElement(['Unpaid', 'Paid']),
        ];
    }
}
