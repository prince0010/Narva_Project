<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Faker\Provider\Base;

class currencyformatterprovider extends Base
{
    
    public function currencyFormat()
    {
        $amount = $this->randomFloat(2, 1, 1000);
        return 'PHP ' . number_format($amount, 2);

    }
   
}
