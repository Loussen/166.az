<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\Candidate::TABLE , function( Blueprint $table )
        {
            $table -> bigIncrements( 'id' );

            $table -> string( 'name' , 55 );
            $table -> string( 'email' , 55 );
            $table -> string( 'phone' , 22 );

            $table -> string( 'cv' , 55 ) -> nullable();

            $table -> integer( 'vacancy_id' ) -> unsigned();

            $table -> boolean( 'is_active' ) -> default( true );
            $table -> boolean( 'is_deleted' ) -> default( false );
            $table -> dateTime( 'created_at' ) -> default( DB ::raw( 'current_timestamp' ) );
            $table -> dateTime( 'updated_at' ) -> nullable() -> default( DB ::raw( 'null on update current_timestamp' ) );
        } );

        Schema ::table( \App\Models\Candidate::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'vacancy_id' ) -> references( 'id' ) -> on( \App\Models\Vacancy::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\Candidate::TABLE );
    }
}
