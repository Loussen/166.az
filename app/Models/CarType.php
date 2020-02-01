<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    const TABLE = 'car_types';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'name_az' ,
            'name_en' ,
            'name_ru'
        ];
}
