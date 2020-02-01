<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    const TABLE = 'site_info';

    protected $table = self::TABLE;

    protected $fillable
        = [
            'photo' ,
            'background' ,
            'og_image' ,

            'title_az' ,
            'title_en' ,
            'title_ru' ,

            'text_az' ,
            'text_en' ,
            'text_ru' ,

            'mission_az' ,
            'mission_en' ,
            'mission_ru' ,

            'transported_objects' ,
            'cleaned_places' ,
            'customer_reviews' ,
            'satisfied_customers' ,

            'address_az' ,
            'address_en' ,
            'address_ru' ,

            'corporate_contact_az' ,
            'corporate_contact_en' ,
            'corporate_contact_ru' ,

            'index' ,

            'mobile' ,
            'email' ,

            'ad_mobile' ,
            'ad_email' ,

            'order_mobile' ,
            'order_email' ,

            'facebook' ,
            'instagram' ,
            'youtube' ,
            'linkedin' ,

            'services_headline_az' ,
            'services_headline_en' ,
            'services_headline_ru' ,

            'cars_headline_az' ,
            'cars_headline_en' ,
            'cars_headline_ru' ,

            'campaigns_headline_az' ,
            'campaigns_headline_en' ,
            'campaigns_headline_ru' ,

            'customers_headline_az' ,
            'customers_headline_en' ,
            'customers_headline_ru' ,

            'faq_headline_az' ,
            'faq_headline_en' ,
            'faq_headline_ru' ,

            'academy_text_az' ,
            'academy_text_en' ,
            'academy_text_ru' ,

            'team_text_az' ,
            'team_text_en' ,
            'team_text_ru' ,

            'about_seo_keywords_az' ,
            'about_seo_keywords_en' ,
            'about_seo_keywords_ru' ,
            'about_seo_description_az' ,
            'about_seo_description_en' ,
            'about_seo_description_ru' ,

            'autopark_seo_keywords_az' ,
            'autopark_seo_keywords_en' ,
            'autopark_seo_keywords_ru' ,
            'autopark_seo_description_az' ,
            'autopark_seo_description_en' ,
            'autopark_seo_description_ru' ,

            'service_seo_keywords_az' ,
            'service_seo_keywords_en' ,
            'service_seo_keywords_ru' ,
            'service_seo_description_az' ,
            'service_seo_description_en' ,
            'service_seo_description_ru' ,

            'campaign_seo_keywords_az' ,
            'campaign_seo_keywords_en' ,
            'campaign_seo_keywords_ru' ,
            'campaign_seo_description_az' ,
            'campaign_seo_description_en' ,
            'campaign_seo_description_ru' ,

            'service_background' ,
            'campaign_background' ,
            'autopark_background' ,
            'team_background' ,
            'blog_background' ,
            'media_background' ,
            'faq_background' ,
            'gallery_background' ,
            'career_background' ,
            'contact_background' ,
        ];
}
