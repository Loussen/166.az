<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    const TABLE = 'vacancies';

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

            'note_az' ,
            'note_en' ,
            'note_ru'
        ];
}
