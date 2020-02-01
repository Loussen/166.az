<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EconomCampaignInclude extends Model
{
    const TABLE = 'econom_campaign_includes';

    protected $table = self::TABLE;

    protected $fillable = [ 'campaign_id' , 'activity_id' ];
}
