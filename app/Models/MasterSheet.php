<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSheet extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'master_sheet';

    // Primary key
    protected $primaryKey = 'id';

    // Indicates if the model should be timestamped.
    public $timestamps = true;

    // Define the fillable properties (optional: mass assignable fields)
    protected $fillable = [
        'our_ref',
        'mail_date',
        'required_date',
        'created_by',
        'art_sent_date',
        'art_approved_date',
        'print_date',
        'invoice_date',
        'invoice_no',
        'cust_ref',
        'description',
        'dn',
        'dn_date',
        'pcs',
        'invoice_value',
        'status'
    ];
}
