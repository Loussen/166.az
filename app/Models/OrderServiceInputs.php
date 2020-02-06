<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderServiceInputs extends Model
{
    const TABLE = 'order_service_inputs';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'order_service_id' ,
            'input_id' ,
            'value'
        ];
}
