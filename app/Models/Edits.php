<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|mixed $date
 * @property mixed $po_no
 * @property mixed $reference_no
 * @property mixed|string $description
 */
class Edits extends Model
{
    protected $table = 'edits';

    protected $fillable = ['date', 'po_no', 'reference_no', 'description'];
}
