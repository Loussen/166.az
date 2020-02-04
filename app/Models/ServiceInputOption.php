<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceInputOption extends Model
{
    const TABLE = 'service_input_options';

    protected $table = self::TABLE;

    protected $fillable = [ 'name_az' , 'name_en' , 'name_ru' , 'input_id', 'coefficient' ];
}
