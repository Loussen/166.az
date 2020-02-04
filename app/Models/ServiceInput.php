<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceInput extends Model
{
    const TABLE = 'service_inputs';

    const TYPES
        = [
            'input'   => 'Text input' ,
            'address' => 'Address input with map' ,
            'select'  => 'Dropdown selectbox' ,
            'number'  => 'Number' ,
            'date'    => 'Date'
        ];

    const STEPS = [ 2 , 3 ];

    protected $table = self::TABLE;

    protected $fillable
        = [
            'type' ,

            'name_az' ,
            'name_en' ,
            'name_ru' ,

            'step' ,
            'service_id',

            'coefficient'
        ];
}
