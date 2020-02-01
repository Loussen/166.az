<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\Service::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'photo' , 55 ) -> nullable();
            $table -> string( 'background' , 55 ) -> nullable();
            $table -> string( 'og_image' , 55 ) -> nullable();

            $table -> string( 'name_az' , 555 );
            $table -> string( 'name_en' , 555 );
            $table -> string( 'name_ru' , 555 );

            $table -> text( 'individual_headline_az' );
            $table -> text( 'individual_headline_en' );
            $table -> text( 'individual_headline_ru' );

            $table -> text( 'corporate_headline_az' );
            $table -> text( 'corporate_headline_en' );
            $table -> text( 'corporate_headline_ru' );

            $table -> text( 'include_headline_az' ) -> nullable();
            $table -> text( 'include_headline_en' ) -> nullable();
            $table -> text( 'include_headline_ru' ) -> nullable();

            $table -> text( 'extra_info_headline_az' ) -> nullable();
            $table -> text( 'extra_info_headline_en' ) -> nullable();
            $table -> text( 'extra_info_headline_ru' ) -> nullable();

            $table -> text( 'individual_description_az' ) -> nullable();
            $table -> text( 'individual_description_en' ) -> nullable();
            $table -> text( 'individual_description_ru' ) -> nullable();

            $table -> text( 'corporate_description_az' ) -> nullable();
            $table -> text( 'corporate_description_en' ) -> nullable();
            $table -> text( 'corporate_description_ru' ) -> nullable();

            $table -> text( 'seo_keywords_az' ) -> nullable();
            $table -> text( 'seo_keywords_en' ) -> nullable();
            $table -> text( 'seo_keywords_ru' ) -> nullable();
            $table -> text( 'seo_description_az' ) -> nullable();
            $table -> text( 'seo_description_en' ) -> nullable();
            $table -> text( 'seo_description_ru' ) -> nullable();

            $table -> integer( 'price' ) -> nullable();

            $table -> integer( 'parent_id' ) -> unsigned() -> nullable();

            $table -> boolean( 'is_active' ) -> default( true );
            $table -> boolean( 'is_deleted' ) -> default( false );
            $table -> dateTime( 'created_at' ) -> default( DB ::raw( 'current_timestamp' ) );
            $table -> dateTime( 'updated_at' ) -> nullable() -> default( DB ::raw( 'null on update current_timestamp' ) );
        } );

        Schema ::table( \App\Models\Service::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'parent_id' ) -> references( 'id' ) -> on( \App\Models\Service::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\Service::TABLE );
    }
}
