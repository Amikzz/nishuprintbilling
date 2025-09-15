<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $invoice_no)
 * @method static whereBetween(string $string, array $array)
 * @method static whereNotNull(string $string)
 * @method static whereNull(string $string)
 * @method static whereDate(string $string, string $string1, \Illuminate\Support\Carbon $from_date)
 */
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
