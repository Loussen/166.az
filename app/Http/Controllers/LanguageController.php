<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 05-Oct-18
 * Time: 14:30
 */

namespace App\Http\Controllers;

use App\Http\Middleware\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class LanguageController
{
    public function changeLanguage( $locale = '' )
    {
        $locale = in_array( $locale , Language ::LOCALES() ) ? $locale : Language::DEFAULT_LANGUAGE;

        session( [ 'locale' => $locale ] );

        App ::setLocale( $locale );

        return back();
    }
}
