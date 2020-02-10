<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    const TABLE = 'orders';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'user_id' ,
            'name' ,
            'phone' ,
            'total' ,
            'status' ,
            'is_order' ,
            '_token' ,
        ];
}
