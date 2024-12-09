<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    /** @use HasFactory<\Database\Factories\ItemsFactory> */
    use HasFactory;

    protected $primaryKey = 'item_code';

    // Disable auto-incrementing if it's not an auto-increment field
    public $incrementing = false;

    // Define the type of the primary key
    protected $keyType = 'string';

    protected $fillable = ['item_code', 'name', 'price', 'description'];
}
