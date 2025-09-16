<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $po_number)
 * @method static create(array $array)
 */
class PurchaseOrderDatabase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderDatabaseFactory> */
    use HasFactory;

    /**
     table name
     */
    protected $table = 'purchase_orders';

    protected $fillable = [
        'date',
        'reference_no',
        'po_no',
        'item_code',
        'color_name',
        'color_no',
        'size',
        'style',
        'upc_no',
        'po_qty',
        'price',
        'customer_id',
        'more1',
        'more2'
    ];

    public function items()
    {
        return $this->hasMany(Items::class, 'item_code', 'item_code'); // Adjust field names as necessary
    }

}
