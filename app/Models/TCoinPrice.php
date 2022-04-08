<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TCoinPrice extends Model
{
    protected $table = 'transaction_coin_price';
 
    protected $fillable = [ 
        'name', 
        'ticker', 
        'coin_id',
        'code',
        'exchange',
        'invalid',
        'record_time',
        'usd',
        'idr',
        'hnst',
        'eth',
        'btc',
        'created_at', 
        'updated_at'
    ];
}
