<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    const TABLE = 'campaigns';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,

            'title_az' ,
            'title_en' ,
            'title_ru' ,

            'individual_headline_az' ,
            'individual_headline_en' ,
            'individual_headline_ru' ,

            'corporate_headline_az' ,
            'corporate_headline_en' ,
            'corporate_headline_ru' ,

            'individual_text_az' ,
            'individual_text_en' ,
            'individual_text_ru' ,

            'corporate_text_az' ,
            'corporate_text_en' ,
            'corporate_text_ru' ,

            'price' ,

            'start_date' ,
            'end_date' ,

            'service_id' ,

            'input'
        ];
}
