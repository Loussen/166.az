<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\ServiceInput::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> enum( 'type' , array_keys( \App\Models\ServiceInput::TYPES ) );

            $table -> text( 'name_az' ) -> nullable();
            $table -> text( 'name_en' ) -> nullable();
            $table -> text( 'name_ru' ) -> nullable();

            $table -> enum( 'step' , [ 2 , 3 ] );

            $table -> integer( 'service_id' ) -> unsigned();

            $table -> boolean( 'is_active' ) -> default( true );
            $table -> boolean( 'is_deleted' ) -> default( false );
            $table -> dateTime( 'created_at' ) -> default( DB ::raw( 'current_timestamp' ) );
            $table -> dateTime( 'updated_at' ) -> nullable() -> default( DB ::raw( 'null on update current_timestamp' ) );
        } );

        Schema ::table( \App\Models\ServiceInput::TABLE , function( Blueprint $table )
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
        Schema ::dropIfExists( \App\Models\ServiceInput::TABLE );
    }
}
