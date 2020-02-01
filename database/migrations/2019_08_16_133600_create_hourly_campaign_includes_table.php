<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourlyCampaignIncludesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\HourlyCampaignInclude::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> integer( 'campaign_id' ) -> unsigned();

            $table -> integer( 'activity_id' ) -> unsigned();
        } );

        Schema ::table( \App\Models\HourlyCampaignInclude::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'campaign_id' ) -> references( 'id' ) -> on( \App\Models\HourlyCampaign::TABLE );

            $table -> foreign( 'activity_id' ) -> references( 'id' ) -> on( \App\Models\HourlyCampaignActivity::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\HourlyCampaignInclude::TABLE );
    }
}
