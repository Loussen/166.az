<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    const TABLE = 'candidates';

    protected $table = self::TABLE;

    protected $guarded = [];
}
