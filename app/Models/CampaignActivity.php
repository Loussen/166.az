<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignActivity extends Model
{
    const TABLE = 'campaign_activities';

    protected $table = self::TABLE;

    protected $fillable = [ 'name_az' , 'name_en' , 'name_ru' , 'campaign_id' ];
}
