<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\CampaignActivity::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'name_az' , 555 );
            $table -> string( 'name_en' , 555 );
            $table -> string( 'name_ru' , 555 );

            $table -> integer( 'campaign_id' ) -> unsigned();
        } );

        Schema ::table( \App\Models\CampaignActivity::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'campaign_id' ) -> references( 'id' ) -> on( \App\Models\Campaign::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\CampaignActivity::TABLE );
    }
}
