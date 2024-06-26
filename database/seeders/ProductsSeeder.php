<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product
        $this->command->warn(PHP_EOL . 'Creating Products....');

        // Creating non-soft-deleted records
        $this->withProgressBar(2, function () {
            Products::factory(5)->create();
        });

        // Creating soft-deleted records
        $this->withProgressBar(2, function () {
            Products::factory(5)->create()->each(function ($product) {
                $product->delete();
            });
        });
        $this->command->info('Products is Created.');
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
