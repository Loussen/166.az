<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HourlyCampaign extends Model
{
    const TABLE = 'hourly_campaigns';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'hour' ,

            'individual_text_az' ,
            'individual_text_en' ,
            'individual_text_ru' ,

            'corporate_text_az' ,
            'corporate_text_en' ,
            'corporate_text_ru' ,

            'day_1_price' ,
            'day_2_price' ,
            'day_3_price' ,

            'service_id'
        ];
}
