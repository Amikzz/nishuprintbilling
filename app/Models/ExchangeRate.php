<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1)
 */
class ExchangeRate extends Model
{
    protected $table = 'exchange_rates';

    protected $fillable = ['currency_from', 'currency_to', 'rate'];


}
