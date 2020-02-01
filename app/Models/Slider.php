<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    const TABLE = 'sliders';

    protected $table = self::TABLE;

    protected $fillable = [ 'photo' , 'link' ];
}
