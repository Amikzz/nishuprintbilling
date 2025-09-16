<?php

namespace App\Models;

use Database\Factories\ItemsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static paginate(int $int)
 * @method static where(string $string, $item_code)
 * @method static whereIn(string $string, $itemCodes)
 * @property mixed $item_code
 * @property mixed $name
 * @property mixed|null $description
 * @property mixed $price
 */
class Items extends Model
{
    /** @use HasFactory<ItemsFactory> */
    use HasFactory;

    protected $primaryKey = 'item_code';

    // Disable auto-incrementing if it's not an auto-increment field
    public $incrementing = false;

    // Define the type of the primary key
    protected $keyType = 'string';

    protected $fillable = ['item_code', 'name', 'price', 'description'];
}
