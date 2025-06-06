<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDatabase extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceDatabaseFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'invoice_no',
        'customer_id',
        'po_number',
        'reference_no',
        'delivery_note_no',
        'no_of_items',
        'status'
    ];
}
