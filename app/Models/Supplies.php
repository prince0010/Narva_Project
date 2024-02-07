<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplies extends Model
{
    use HasFactory;


    protected $fillable = [
        'supplier_num',
        'part_num',
        'brand',
        'model',
        'code',
        'quantity',
    ];
}
