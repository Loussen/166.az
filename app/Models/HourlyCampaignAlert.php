<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HourlyCampaignAlert extends Model
{
    const TABLE = 'hourly_campaign_alerts';

    protected $table = self::TABLE;

    protected $fillable = [ 'text_az' , 'text_en' , 'text_ru' , 'campaign_id' ];
}
