<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class credit_users extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'credit_users';
    
    
    protected $fillable = [
        'credit_name',
        'credit_limit'
    ];

    protected $guarded = ['credit_limit'];
    
    public function credit_inform(){
        return $this->hasMany(credit_inform::class);
    }
}
