<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    const TABLE = 'cars';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,
            'background' ,
            'og_image' ,
            'palet_photo' ,

            'title_az' ,
            'title_en' ,
            'title_ru' ,

            'headline_az' ,
            'headline_en' ,
            'headline_ru' ,

            'text_az' ,
            'text_en' ,
            'text_ru' ,

            'length' ,
            'height' ,
            'width' ,
            'palet' ,
            'weight' ,

            'seo_keywords_az' ,
            'seo_keywords_en' ,
            'seo_keywords_ru' ,
            'seo_description_az' ,
            'seo_description_en' ,
            'seo_description_ru' ,

            'type_id'
        ];
}
