<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $delivery_note_no)
 * @method static findOrFail(int $id)
 * @method static find($id)
 */
class ReturnDatabase extends Model
{

    use HasFactory;

    protected $table = 'return_databases';

    protected $fillable = [
        'invoice_number',
        'delivery_note_no',
        'new_dnote_no',
        'po_no',
        'item_code',
        'color_name',
        'color_no',
        'size',
        'style',
        'upc_no',
        'po_qty',
        'price',
        'more1',
        'more2',
    ];

}
