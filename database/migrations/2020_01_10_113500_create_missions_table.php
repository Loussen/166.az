<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\Mission::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'photo' , 55 ) -> nullable();

            $table -> string( 'title_az' , 555 );
            $table -> string( 'title_en' , 555 );
            $table -> string( 'title_ru' , 555 );

            $table -> boolean( 'is_active' ) -> default( true );
            $table -> boolean( 'is_deleted' ) -> default( false );
            $table -> dateTime( 'created_at' ) -> default( DB ::raw( 'current_timestamp' ) );
            $table -> dateTime( 'updated_at' ) -> nullable() -> default( DB ::raw( 'null on update current_timestamp' ) );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\Mission::TABLE );
    }
}
