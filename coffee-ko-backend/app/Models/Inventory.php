<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';  // dito mo nilagay ito

    protected $fillable = [
        'name',
        'category',
        'stock',
        'price',
        'threshold',
    ];
}
