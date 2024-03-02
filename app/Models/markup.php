<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class markup extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'markup'; 

    protected $fillable = [
        'markup_name',
        'markup_rate'
    ];

  
    public function sales(){
        return $this->hasMany(sales::class);
    }

}
