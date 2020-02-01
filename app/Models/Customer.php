<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    const TABLE = 'customers';

    protected $table = self::TABLE;

    protected $fillable = [ 'photo' ];
}
