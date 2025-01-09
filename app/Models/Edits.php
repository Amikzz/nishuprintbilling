<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edits extends Model
{
    protected $table = 'edits';

    protected $fillable = ['date', 'po_no', 'reference_no', 'description'];
}
