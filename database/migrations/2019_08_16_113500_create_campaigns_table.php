<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\Campaign::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'photo' , 55 ) -> nullable();

            $table -> string( 'title_az' , 555 );
            $table -> string( 'title_en' , 555 );
            $table -> string( 'title_ru' , 555 );

            $table -> text( 'individual_headline_az' );
            $table -> text( 'individual_headline_en' );
            $table -> text( 'individual_headline_ru' );

            $table -> text( 'corporate_headline_az' );
            $table -> text( 'corporate_headline_en' );
            $table -> text( 'corporate_headline_ru' );

            $table -> text( 'individual_text_az' ) -> nullable();
            $table -> text( 'individual_text_en' ) -> nullable();
            $table -> text( 'individual_text_ru' ) -> nullable();

            $table -> text( 'corporate_text_az' ) -> nullable();
            $table -> text( 'corporate_text_en' ) -> nullable();
            $table -> text( 'corporate_text_ru' ) -> nullable();

            $table -> decimal( 'price' , 8 , 2 );

            $table -> date( 'start_date' );
            $table -> date( 'end_date' );

            $table -> integer( 'service_id' ) -> unsigned();

            $table -> string( 'input' , 11 ) -> default( 'email' );

            $table -> boolean( 'is_active' ) -> default( true );
            $table -> boolean( 'is_deleted' ) -> default( false );
            $table -> dateTime( 'created_at' ) -> default( DB ::raw( 'current_timestamp' ) );
            $table -> dateTime( 'updated_at' ) -> nullable() -> default( DB ::raw( 'null on update current_timestamp' ) );
        } );

        Schema ::table( \App\Models\Campaign::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'service_id' ) -> references( 'id' ) -> on( \App\Models\Service::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\Campaign::TABLE );
    }
}
