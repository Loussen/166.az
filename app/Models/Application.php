<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    const TABLE = 'applications';

    protected $table = self::TABLE;

    protected $guarded = [];
}
