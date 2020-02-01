<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\Faq::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'individual_question_az' , 555 ) -> nullable();
            $table -> string( 'individual_question_en' , 555 ) -> nullable();
            $table -> string( 'individual_question_ru' , 555 ) -> nullable();

            $table -> string( 'corporate_question_az' , 555 ) -> nullable();
            $table -> string( 'corporate_question_en' , 555 ) -> nullable();
            $table -> string( 'corporate_question_ru' , 555 ) -> nullable();

            $table -> text( 'individual_answer_az' ) -> nullable();
            $table -> text( 'individual_answer_en' ) -> nullable();
            $table -> text( 'individual_answer_ru' ) -> nullable();

            $table -> text( 'corporate_answer_az' );
            $table -> text( 'corporate_answer_en' );
            $table -> text( 'corporate_answer_ru' );

            $table -> integer( 'service_id' ) -> unsigned();

            $table -> boolean( 'is_active' ) -> default( true );
            $table -> boolean( 'is_deleted' ) -> default( false );
            $table -> dateTime( 'created_at' ) -> default( DB ::raw( 'current_timestamp' ) );
            $table -> dateTime( 'updated_at' ) -> nullable() -> default( DB ::raw( 'null on update current_timestamp' ) );
        } );

        Schema ::table( \App\Models\Faq::TABLE , function( Blueprint $table )
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
        Schema ::dropIfExists( \App\Models\Faq::TABLE );
    }
}
