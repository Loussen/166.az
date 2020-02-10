<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    const TABLE = 'coupon';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'phone' ,
            'code' ,
            'approve'
        ];
}
