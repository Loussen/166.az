<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacancyRequirement extends Model
{
    const TABLE = 'vacancy_requirements';

    protected $table = self::TABLE;

    protected $fillable = [ 'name_az' , 'name_en' , 'name_ru' , 'vacancy_id' ];
}
