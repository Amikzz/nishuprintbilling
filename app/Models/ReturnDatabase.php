<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDatabase extends Model
{

    use HasFactory;

    protected $table = 'return_databases';

    protected $fillable = [
        'invoice_number',
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
