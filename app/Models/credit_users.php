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
        return $this->hasMany(credit_inform::class, 'credit_users_id');
    }

    public function transaction_details()
    {
        return $this->hasManyThrough(
            transaction_details::class,
            credit_inform::class,
            'credit_users_id', // Foreign key on credit_inform table
            'credit_inform_id', // Foreign key on transaction_details table
            'id', // Local key on credit_users table
            'id' // Local key on credit_inform table
        );
    }

    public function getBalanceAttribute()
    {
        return $this->transaction_details()->sum('balance');
    }
}
