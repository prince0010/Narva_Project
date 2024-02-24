<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Prod_Types;
use App\Models\Supplier;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call([
        //     UserSeeder::class,
        // ]);

        // User
        $this->command->warn(PHP_EOL . 'Creating User....');
        $user = $this->withProgressBar(1, fn () => User::factory(1)->create([
            'name' => 'Kenth Balagonsa',
            'email' => 'kbalagonsa001@gmail.com',
            'password' => Hash::make('kenthbalagonsa221'),
        ]));
        $this->command->info('User Data is Created.');

        // Product Types
        $this->command->warn(PHP_EOL . 'Creating Product Type....');
        $producttype = $this->withProgressBar(10, fn () => Prod_Types::factory()->count(10)->create());
        $this->command->info('Product Type is Created.');

        // Supplier
        $this->command->warn(PHP_EOL . 'Creating Supplier....');
        $producttype = $this->withProgressBar(10, fn () => Supplier::factory()->count(10)->create());
        $this->command->info('Supplier is Created.');

        // Products
        
       
    }






    // With Progress Bar Function
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



//Create a static user of Admin Here Or sa UserSeeder