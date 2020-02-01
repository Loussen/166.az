<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    const TABLE = 'subscribes';

    protected $table = self::TABLE;

    protected $guarded = [];
}
