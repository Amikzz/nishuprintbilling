<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];}
