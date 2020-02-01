<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    const TABLE = 'callbacks';

    protected $table = self::TABLE;

    protected $guarded = [];
}
