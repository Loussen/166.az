<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const TABLE = 'posts';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,
            'background' ,
            'og_image' ,

            'title_az' ,
            'title_en' ,
            'title_ru' ,

            'text_az' ,
            'text_en' ,
            'text_ru' ,

            'headline_az' ,
            'headline_en' ,
            'headline_ru' ,

            'date' ,

            'is_new' ,

            'type' ,

            'service_id' ,

            'view_count' ,

            'seo_keywords_az' ,
            'seo_keywords_en' ,
            'seo_keywords_ru' ,
            'seo_description_az' ,
            'seo_description_en' ,
            'seo_description_ru'
        ];
}
