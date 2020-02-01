<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEconomCampaignIncludesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\EconomCampaignInclude::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> integer( 'campaign_id' ) -> unsigned();

            $table -> integer( 'activity_id' ) -> unsigned();
        } );

        Schema ::table( \App\Models\EconomCampaignInclude::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'campaign_id' ) -> references( 'id' ) -> on( \App\Models\EconomCampaign::TABLE );

            $table -> foreign( 'activity_id' ) -> references( 'id' ) -> on( \App\Models\EconomCampaignActivity::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\EconomCampaignInclude::TABLE );
    }
}
