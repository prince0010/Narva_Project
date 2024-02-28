<?php

namespace Database\Seeders;

use App\Models\Credit_Info;
use App\Providers\currencyformatterprovider;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Helper\ProgressBar;
use Closure;

class CreditInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL . 'Creating Credit Information....');
    
        // Creating non-soft-deleted records
        $this->withProgressBar(2, function () {
            Credit_Info::factory(5)->create();
        });
    
        // Access the application instance using the app() function
        $faker = app(FakerGenerator::class);
        $faker->addProvider(new currencyformatterprovider($faker));
    
        // Creating soft-deleted records
        $this->withProgressBar(2, function () use ($faker) {
            Credit_Info::factory(5)->create()->each(function ($creditInfo) use ($faker) {
                $creditInfo->delete();
            });
        });
    
        $this->command->info('Credit Information created successfully.');
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $collection = $createCollectionOfOne();

            // Check if the returned value is not null
            if (!is_null($collection)) {
                $items = $items->merge($collection);
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
