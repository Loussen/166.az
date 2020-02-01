<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    const TABLE = 'employees';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,

            'name_az' ,
            'name_en' ,
            'name_ru' ,

            'position_az' ,
            'position_en' ,
            'position_ru' ,

            'mobile' ,
            'email' ,

            'facebook' ,
            'instagram' ,
            'twitter' ,
            'linkedin'
        ];
}
