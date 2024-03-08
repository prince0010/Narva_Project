<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class credit_names extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'credit_names'; 
    protected $fillable = [
        'credit_name',
        'downpayment',
        'dp_date'
    ];

    public function credit_info(){
        return $this->hasOne(credit_info::class);
    }
}
