<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create( \App\Models\Site::TABLE , function( Blueprint $table )
        {
            $table -> increments( 'id' );

            $table -> string( 'photo' , 55 ) -> nullable();
            $table -> string( 'background' , 55 ) -> nullable();
            $table -> string( 'og_image' , 55 ) -> nullable();

            $table -> string( 'title_az' , 555 ) -> nullable();
            $table -> string( 'title_en' , 555 ) -> nullable();
            $table -> string( 'title_ru' , 555 ) -> nullable();

            $table -> text( 'text_az' ) -> nullable();
            $table -> text( 'text_en' ) -> nullable();
            $table -> text( 'text_ru' ) -> nullable();

            $table -> text( 'mission_az' ) -> nullable();
            $table -> text( 'mission_en' ) -> nullable();
            $table -> text( 'mission_ru' ) -> nullable();

            $table -> integer( 'transported_objects' ) -> nullable();
            $table -> integer( 'cleaned_places' ) -> nullable();
            $table -> integer( 'customer_reviews' ) -> nullable();
            $table -> integer( 'satisfied_customers' ) -> nullable();

            $table -> string( 'address_az' , 555 ) -> nullable();
            $table -> string( 'address_en' , 555 ) -> nullable();
            $table -> string( 'address_ru' , 555 ) -> nullable();

            $table -> string( 'corporate_contact_az' , 555 ) -> nullable();
            $table -> string( 'corporate_contact_en' , 555 ) -> nullable();
            $table -> string( 'corporate_contact_ru' , 555 ) -> nullable();

            $table -> string( 'index' , 22 ) -> nullable();

            $table -> string( 'mobile' , 22 ) -> nullable();
            $table -> string( 'email' , 55 ) -> nullable();

            $table -> string( 'ad_mobile' , 22 ) -> nullable();
            $table -> string( 'ad_email' , 55 ) -> nullable();

            $table -> string( 'order_mobile' , 22 ) -> nullable();
            $table -> string( 'order_email' , 55 ) -> nullable();

            $table -> string( 'facebook' , 111 ) -> nullable();
            $table -> string( 'instagram' , 111 ) -> nullable();
            $table -> string( 'youtube' , 111 ) -> nullable();
            $table -> string( 'linkedin' , 111 ) -> nullable();

            $table -> string( 'services_headline_az' , 555 ) -> nullable();
            $table -> string( 'services_headline_en' , 555 ) -> nullable();
            $table -> string( 'services_headline_ru' , 555 ) -> nullable();

            $table -> string( 'cars_headline_az' , 555 ) -> nullable();
            $table -> string( 'cars_headline_en' , 555 ) -> nullable();
            $table -> string( 'cars_headline_ru' , 555 ) -> nullable();

            $table -> string( 'campaigns_headline_az' , 555 ) -> nullable();
            $table -> string( 'campaigns_headline_en' , 555 ) -> nullable();
            $table -> string( 'campaigns_headline_ru' , 555 ) -> nullable();

            $table -> string( 'customers_headline_az' , 555 ) -> nullable();
            $table -> string( 'customers_headline_en' , 555 ) -> nullable();
            $table -> string( 'customers_headline_ru' , 555 ) -> nullable();

            $table -> string( 'faq_headline_az' , 555 ) -> nullable();
            $table -> string( 'faq_headline_en' , 555 ) -> nullable();
            $table -> string( 'faq_headline_ru' , 555 ) -> nullable();

            $table -> text( 'academy_text_az' ) -> nullable();
            $table -> text( 'academy_text_en' ) -> nullable();
            $table -> text( 'academy_text_ru' ) -> nullable();

            $table -> text( 'team_text_az' ) -> nullable();
            $table -> text( 'team_text_en' ) -> nullable();
            $table -> text( 'team_text_ru' ) -> nullable();

            $table -> text( 'about_seo_keywords_az' ) -> nullable();
            $table -> text( 'about_seo_keywords_en' ) -> nullable();
            $table -> text( 'about_seo_keywords_ru' ) -> nullable();
            $table -> text( 'about_seo_description_az' ) -> nullable();
            $table -> text( 'about_seo_description_en' ) -> nullable();
            $table -> text( 'about_seo_description_ru' ) -> nullable();

            $table -> text( 'autopark_seo_keywords_az' ) -> nullable();
            $table -> text( 'autopark_seo_keywords_en' ) -> nullable();
            $table -> text( 'autopark_seo_keywords_ru' ) -> nullable();
            $table -> text( 'autopark_seo_description_az' ) -> nullable();
            $table -> text( 'autopark_seo_description_en' ) -> nullable();
            $table -> text( 'autopark_seo_description_ru' ) -> nullable();

            $table -> text( 'service_seo_keywords_az' ) -> nullable();
            $table -> text( 'service_seo_keywords_en' ) -> nullable();
            $table -> text( 'service_seo_keywords_ru' ) -> nullable();
            $table -> text( 'service_seo_description_az' ) -> nullable();
            $table -> text( 'service_seo_description_en' ) -> nullable();
            $table -> text( 'service_seo_description_ru' ) -> nullable();

            $table -> text( 'campaign_seo_keywords_az' ) -> nullable();
            $table -> text( 'campaign_seo_keywords_en' ) -> nullable();
            $table -> text( 'campaign_seo_keywords_ru' ) -> nullable();
            $table -> text( 'campaign_seo_description_az' ) -> nullable();
            $table -> text( 'campaign_seo_description_en' ) -> nullable();
            $table -> text( 'campaign_seo_description_ru' ) -> nullable();

            $table -> string( 'service_background' , 55 ) -> nullable();
            $table -> string( 'campaign_background' , 55 ) -> nullable();
            $table -> string( 'autopark_background' , 55 ) -> nullable();
            $table -> string( 'team_background' , 55 ) -> nullable();
            $table -> string( 'blog_background' , 55 ) -> nullable();
            $table -> string( 'media_background' , 55 ) -> nullable();
            $table -> string( 'faq_background' , 55 ) -> nullable();
            $table -> string( 'gallery_background' , 55 ) -> nullable();
            $table -> string( 'career_background' , 55 ) -> nullable();
            $table -> string( 'contact_background' , 55 ) -> nullable();

            $table -> enum( 'unique' , [ 'unique' ] ) -> default( 'unique' );
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
        Schema ::dropIfExists( \App\Models\Site::TABLE );
    }
}
