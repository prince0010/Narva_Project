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
        
        $this->call(ProductsSeeder::class);
        $this->call(ProductTypeSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(UserSeeder::class);
        
       
    }


}



//Create a static user of Admin Here Or sa UserSeeder