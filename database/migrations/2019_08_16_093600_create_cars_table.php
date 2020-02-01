<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\Car::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'photo' , 55 ) -> nullable();
            $table -> string( 'background' , 55 ) -> nullable();
            $table -> string( 'og_image' , 55 ) -> nullable();
            $table -> string( 'palet_photo' , 55 ) -> nullable();

            $table -> string( 'title_az' , 555 );
            $table -> string( 'title_en' , 555 );
            $table -> string( 'title_ru' , 555 );

            $table -> text( 'headline_az' );
            $table -> text( 'headline_en' );
            $table -> text( 'headline_ru' );

            $table -> text( 'text_az' );
            $table -> text( 'text_en' );
            $table -> text( 'text_ru' );

            $table -> string( 'length' , 11 );
            $table -> string( 'height' , 11 );
            $table -> string( 'width' , 11 );
            $table -> integer( 'palet' );
            $table -> string( 'weight' , 11 );

            $table -> text( 'seo_keywords_az' ) -> nullable();
            $table -> text( 'seo_keywords_en' ) -> nullable();
            $table -> text( 'seo_keywords_ru' ) -> nullable();
            $table -> text( 'seo_description_az' ) -> nullable();
            $table -> text( 'seo_description_en' ) -> nullable();
            $table -> text( 'seo_description_ru' ) -> nullable();

            $table -> integer( 'type_id' ) -> unsigned();

            $table -> boolean( 'is_active' ) -> default( true );
            $table -> boolean( 'is_deleted' ) -> default( false );
            $table -> dateTime( 'created_at' ) -> default( DB ::raw( 'current_timestamp' ) );
            $table -> dateTime( 'updated_at' ) -> nullable() -> default( DB ::raw( 'null on update current_timestamp' ) );
        } );

        Schema ::table( \App\Models\Car::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'type_id' ) -> references( 'id' ) -> on( \App\Models\CarType::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\Car::TABLE );
    }
}
