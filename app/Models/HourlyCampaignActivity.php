<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HourlyCampaignActivity extends Model
{
    const TABLE = 'hourly_campaign_activities';

    protected $table = self::TABLE;

    protected $fillable = [ 'name_az' , 'name_en' , 'name_ru' , 'service_id' ];
}
