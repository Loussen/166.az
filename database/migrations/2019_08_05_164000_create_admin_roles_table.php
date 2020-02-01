<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\AdminRole::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'role' , 55 );

            $table -> integer( 'admin_id' ) -> unsigned();
        } );

        Schema ::table( \App\Models\AdminRole::TABLE , function( Blueprint $table )
        {
            $table -> foreign( 'admin_id' ) -> references( 'id' ) -> on( \App\Admin::TABLE );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists( \App\Models\AdminRole::TABLE );
    }
}
