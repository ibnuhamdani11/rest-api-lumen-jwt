<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'trx_id', 
        'user_id', 
        'amount', 
        'created_at', 
        'updated_at'
    ];
}
