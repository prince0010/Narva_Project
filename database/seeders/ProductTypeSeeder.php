<?php

namespace Database\Seeders;

use App\Models\Prod_Types;
use Illuminate\Database\Seeder;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product Types
        $this->command->warn(PHP_EOL . 'Creating Product Type....');
        $producttype = $this->withProgressBar(2, fn () => Prod_Types::factory(5)->create());
        $this->command->info('Product Type is Created.');

    }




    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
