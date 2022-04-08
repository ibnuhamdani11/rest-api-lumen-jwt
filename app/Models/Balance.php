<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $table = 'balance';

    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    protected $fillable = [ 
        'user_id', 
        'amount_available', 
        'created_at', 
        'updated_at'
    ];
}
