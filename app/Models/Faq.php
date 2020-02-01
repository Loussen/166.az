<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    const TABLE = 'faq';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'individual_question_az' ,
            'individual_question_en' ,
            'individual_question_ru' ,

            'corporate_question_az' ,
            'corporate_question_en' ,
            'corporate_question_ru' ,

            'individual_answer_az' ,
            'individual_answer_en' ,
            'individual_answer_ru' ,

            'corporate_answer_az' ,
            'corporate_answer_en' ,
            'corporate_answer_ru' ,

            'service_id'
        ];
}
