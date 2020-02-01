<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    const TABLE = 'translations';

    protected $table = self::TABLE;

    protected $fillable = [ 'key' , 'az' , 'en' , 'ru' ];
}
