<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderServices extends Model
{
    const TABLE = 'order_services';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'order_id' ,
            'service_id' ,
            'price' ,
            'child_service_id'
        ];
}
