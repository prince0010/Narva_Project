<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class credit_names extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'credit_name',
        'credit_info_ID'
    ];

    public function credit_info(){
        return $this->belongsTo(credit_info::class, 'credit_info_ID');
    }
}
