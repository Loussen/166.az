<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EconomCampaign extends Model
{
    const TABLE = 'econom_campaigns';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'title_az' ,
            'title_en' ,
            'title_ru' ,

            'individual_text_az' ,
            'individual_text_en' ,
            'individual_text_ru' ,

            'corporate_text_az' ,
            'corporate_text_en' ,
            'corporate_text_ru' ,

            'price' ,

            'service_id'
        ];
}
