<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    const TABLE = 'services';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,
            'background' ,
            'og_image' ,

            'name_az' ,
            'name_en' ,
            'name_ru' ,

            'individual_headline_az' ,
            'individual_headline_en' ,
            'individual_headline_ru' ,

            'corporate_headline_az' ,
            'corporate_headline_en' ,
            'corporate_headline_ru' ,

            'include_headline_az' ,
            'include_headline_en' ,
            'include_headline_ru' ,

            'extra_info_headline_az' ,
            'extra_info_headline_en' ,
            'extra_info_headline_ru' ,

            'individual_description_az' ,
            'individual_description_en' ,
            'individual_description_ru' ,

            'corporate_description_az' ,
            'corporate_description_en' ,
            'corporate_description_ru' ,

            'seo_keywords_az' ,
            'seo_keywords_en' ,
            'seo_keywords_ru' ,
            'seo_description_az' ,
            'seo_description_en' ,
            'seo_description_ru' ,

            'price' ,

            'parent_id'
        ];
}
