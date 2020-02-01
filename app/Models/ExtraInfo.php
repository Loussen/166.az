<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraInfo extends Model
{
    const TABLE = 'extra_info';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,

            'title_az' ,
            'title_en' ,
            'title_ru' ,

            'service_id'
        ];
}
