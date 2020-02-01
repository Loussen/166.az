<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HourlyCampaignInclude extends Model
{
    const TABLE = 'hourly_campaign_includes';

    protected $table = self::TABLE;

    protected $fillable = [ 'campaign_id' , 'activity_id' ];
}
