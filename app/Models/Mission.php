<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    const TABLE = 'missions';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,

            'title_az' ,
            'title_en' ,
            'title_ru'
        ];
}
