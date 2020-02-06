<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTransactions extends Model
{
    const TABLE = 'order_transactions';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'order_id' ,
            'OrderId' ,
            'SessionId',
            'result'
        ];
}
