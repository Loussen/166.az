<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    const TABLE = 'photos';

    protected $table = self::TABLE;

    protected $fillable = [ 'photo' , 'service_id' ];
}
