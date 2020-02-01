<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\Language;
use App\Http\Service\Exception;
use App\Http\Service\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests , DispatchesJobs , ValidatesRequests;

    protected static function UPLOAD_DIR()
    {
        return public_path() . env( 'UPLOAD_DIR' );
    }

    protected $locale;
    protected $validator;


    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this -> locale = App ::getLocale();

        $this -> validator = new Validator();
    }


    public static function BUILD_TREE( $elements , $parent = null )
    {
        $branch = [];

        foreach( $elements as $element )
        {
            if( $element -> parent_id == $parent )
            {
                $element -> children = self ::BUILD_TREE( $elements , $element -> id );

                $branch[] = $element;
            }
        }

        return $branch;
    }


    protected function upload( Request $request , $dir , $table , $id , $columns , $allowedExtensions = [ 'png' , 'jpeg' , 'jpg' ] , $name = null )
    {
        foreach( $columns as $column )
        {
            $resize = false;

            if( is_array( $column ) )
            {
                $resize = true;
                $column = $column[ 0 ];
            }

            $name = $name ? $name : md5( time() . $column . uniqid() );

            if( isset( $_FILES[ $column ] ) && strlen( $_FILES[ $column ][ 'name' ] ) && strlen( $_FILES[ $column ][ 'tmp_name' ] ) )
            {
                $file = $_FILES[ $column ];
                $img  = $file[ 'name' ];
                $tmp  = $file[ 'tmp_name' ];
                $ext  = strtolower( pathinfo( $img , PATHINFO_EXTENSION ) );

                if( in_array( $ext , $allowedExtensions ) )
                {
                    $fileName = $name . '.' . $ext;

                    if( $resize )
                    {
                        $fileName_ = $name . '_avatar.' . $ext;

                        if( $ext == 'jpg' || $ext == 'jpeg' ) $src = imagecreatefromjpeg( $tmp );

                        else  $src = imagecreatefrompng( $tmp );

                        list( $width , $height ) = getimagesize( $tmp );

                        $width_ = 400;

                        $height_ = intval( ( $width_ * $height ) / $width );

                        $new_img = imagecreatetruecolor( $width_ , $height_ );

                        imagecopyresampled( $new_img , $src , 0 , 0 , 0 , 0 , $width_ , $height_ , $width , $height );

                        imagejpeg( $new_img , public_path( 'uploads' ) . '/' . $dir . '/' . $fileName_ , 100 );
                    }

                    move_uploaded_file( $tmp , public_path( 'uploads' ) . '/' . $dir . '/' . $fileName );

                    DB ::table( $table )
                       -> where( 'id' , $id )
                       -> update( [ $column => $fileName ] )
                    ;
                }
            }
        }
    }


    protected function uploadMultipleFiles( Request $request , $input = [] )
    {
        $insert   = $input[ 'insert' ] ?? [];
        $type     = $input[ 'type' ] ?? 'photo';
        $key      = $input[ 'key' ] ?? 'images';
        $parent   = $input[ 'parent' ] ?? 'service';
        $parentId = $input[ 'parentId' ] ?? $parent . '_id';
        $id       = $input[ 'id' ] ?? false;
        $table    = $input[ 'table' ] ?? $parent . '_images';
        $column   = $input[ 'column' ] ?? 'path';
        $dir      = $input[ 'dir' ] ?? '/service/images';

        $allowedExtensions = [
            'photo' => [ 'png' , 'jpeg' , 'jpg' ]
        ];

        $files = $request -> files -> get( $key );

        $result = [];

        if( $files )
        {
            foreach( $files as $file )
            {
                $ext = $file -> getClientOriginalExtension();

                if( in_array( $ext , $allowedExtensions[ $type ] ) )
                {
                    $fileName = md5( time() . uniqid() ) . '.' . $ext;

                    $insert[ $column ] = $fileName;

                    if( $id ) $insert[ $parentId ] = $id;

                    $id = DB ::table( $table ) -> insertGetId( $insert );

                    $file -> move( public_path( 'uploads' ) . $dir , $fileName );

                    $result[] = [ 'id' => $id , $column => $insert[ $column ] ];
                }
            }
        }


        $deletedFiles = json_decode( $request -> request -> get( 'deleted_files' ) );

        if( is_array( $deletedFiles ) )
            foreach( $deletedFiles as $deletedFile )
                DB ::table( $table ) -> where( 'id' , intval( $deletedFile ) ) -> update( [ 'is_deleted' => 1 ] );


        return $result;
    }


    protected function _404()
    {
        return response() -> view( 'errors.404' );
    }


    protected function getGRecapchaResponse( $gRecaptchaResponse )
    {
        $data = [ 'secret' => env( 'RE_CAPTCHA_PRIVATE_KEY' ) , 'response' => $gRecaptchaResponse ];

        $ch = curl_init();

        curl_setopt( $ch , CURLOPT_URL , 'https://www.google.com/recaptcha/api/siteverify' );
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt( $ch , CURLOPT_CONNECTTIMEOUT , 5 );
        curl_setopt( $ch , CURLOPT_TIMEOUT , 5 );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $data );

        $data     = curl_exec( $ch );
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );

        curl_close( $ch );

        return ( $httpCode >= 200 && $httpCode < 300 ) ? $data : false;
    }


    protected function getException( \Exception $exception )
    {
        Exception ::log( $exception );

        $exception = [
            'exception' => [
                'file'    => $exception -> getFile() ,
                'line'    => $exception -> getLine() ,
                'message' => $exception -> getMessage()
            ]
        ];

        return response() -> json( array_merge( [ 'status' => 'error' ] , $exception ) );
    }


    protected function configureList( Request $request , $SELECT , $FROM , $WHERE , array $filter , array $sort , $groupBy = '' , $having = '' , $havingClause = '' , $defaultStart = 0 )
    {
        $bindings = [];

        $columnFilter = $searchFilter = $compareFilter = $havingFilter = '';

        $flag = false;

        foreach( $filter as $column => $c )
        {
            $dbColumn = $c[ 'db' ] ?? $column;

            if( isset( $c[ 'type' ] ) )
            {
                if( $c[ 'type' ] == 'search' )
                {
                    $dbColumns = is_array( $dbColumn ) ? $dbColumn : [ $dbColumn ];

                    if( $request -> request -> get( $column ) )
                    {
                        $thisFilter = '';

                        foreach( $dbColumns as $dbColumn )
                        {
                            $thisFilter .= strlen( $thisFilter ) ? " OR " : '';

                            $thisFilter .= " $dbColumn LIKE '%" . $this -> clear( $request -> request -> get( $column ) ) . "%' ";

                            $bindings[ $column ] = $request -> request -> get( $column );
                        }

                        $columnFilter .= " AND ( $thisFilter ) ";
                    }

                    foreach( $dbColumns as $dbColumn )
                    {
                        if( $request -> request -> get( 'search' ) )
                        {
                            $searchFilter .= strlen( $searchFilter ) ? '' : " AND ( ";

                            if( $flag ) $searchFilter .= ' OR ';
                            $flag = true;

                            $searchFilter .= " $dbColumn LIKE '%" . $this -> clear( $request -> request -> get( 'search' ) ) . "%' ";
                        }
                    }
                }
                elseif( $c[ 'type' ] == 'compare' )
                {
                    if( $request -> request -> get( $column ) )
                    {
                        $first = substr( $request -> request -> get( $column ) , 0 , 1 );

                        $comparison = in_array( $first , [ '>' , '<' ] ) ? $first : '=';

                        $value = intval( substr( $request -> request -> get( $column ) , 1 ) );

                        if( strlen( $groupBy ) && isset( $c[ 'having' ] ) && $c[ 'having' ] )
                        {
                            $havingFilter .= strlen( $havingFilter ) ? '' : ' HAVING ';
                            $havingFilter .= strlen( $havingFilter ) > 11 ? ' AND ' : '';
                            $havingFilter .= " $dbColumn $comparison $value ";
                        }
                        else $compareFilter .= " AND $dbColumn $comparison $value ";

                        $bindings[ $column ] = $request -> request -> get( $value );
                    }
                }

                elseif( $c[ 'type' ] == 'between' )
                {
                    $dbColumn = $column == 'date' ? "DATE( $dbColumn )" : $dbColumn;

                    if( $request -> request -> get( $column . 'From' ) )
                    {
                        $from = $request -> request -> get( $column . 'From' );

                        if( strlen( $groupBy ) && isset( $c[ 'having' ] ) && $c[ 'having' ] )
                        {
                            $havingFilter .= strlen( $havingFilter ) ? '' : ' HAVING ';
                            $havingFilter .= strlen( $havingFilter ) > 11 ? ' AND ' : '';
                            $havingFilter .= " $dbColumn >= '$from' ";
                        }
                        else $compareFilter .= " AND $dbColumn >= '$from' ";

                        $bindings[ $column . 'From' ] = $request -> request -> get( $column . 'From' );
                    }

                    if( $request -> request -> get( $column . 'To' ) )
                    {
                        $to = $request -> request -> get( $column . 'To' );

                        if( strlen( $groupBy ) && isset( $c[ 'having' ] ) && $c[ 'having' ] )
                        {
                            $havingFilter .= strlen( $havingFilter ) ? '' : ' HAVING ';
                            $havingFilter .= strlen( $havingFilter ) > 11 ? ' AND ' : '';
                            $havingFilter .= " $dbColumn <= '$to' ";
                        }
                        else $compareFilter .= " AND $dbColumn <= '$to' ";

                        $bindings[ $column . 'To' ] = $request -> request -> get( $column . 'To' );
                    }
                }
            }

            elseif( $request -> request -> get( $column ) !== null && $request -> request -> get( $column ) !== 'All' )
            {
                $columnFilter .= " AND $dbColumn = '" . $this -> clear( $request -> request -> get( $column ) ) . "'";

                $bindings[ $column ] = $request -> request -> get( $column );
            }
        }

        if( $request -> request -> get( 'search' ) )
        {
            $searchFilter .= strlen( $searchFilter ) ? " ) " : '';

            $bindings[ 'search' ] = $request -> request -> get( 'search' );
        }

        $WHERE .= strlen( $WHERE ) ? '' : " WHERE 1 = 1 ";
        $WHERE .= ( $columnFilter . ' ' . $searchFilter . ' ' . $compareFilter );


        $per = intval( $request -> request -> get( 'per' ) );
        $per = $per && $per <= 166 ? $per : 10;

        $page = $this -> validator -> validateId( $request -> request -> get( 'page' ) );

        $page = $page ? $page : 1;

        $GROUP_BY = strlen( $groupBy ) ? ' GROUP BY ' . $groupBy : '';

        $havingClause = strlen( $havingClause ) ? ( ( strlen( $havingFilter ) ? '' : ' HAVING ' ) . $havingClause ) : '';

        $baseQuery = $FROM . ' ' . $WHERE . ' ' . $GROUP_BY . ' ' . $havingFilter . ' ' . $havingClause;

        $allQuery = ' SELECT COUNT(0) `count` ' . ( strlen( $groupBy ) ? " FROM ( SELECT COUNT(0) `count` " . ( strlen( $having ) && strlen( $havingClause ) ? " , $having " : '' ) : '' ) . $baseQuery . ( strlen( $groupBy ) ? ' ) t ' : '' ) . ' ; ';

        $all = DB ::select( $allQuery , $bindings )[ 0 ] -> count;

        $count = ceil( $all / $per );

        if( $count && $page > $count ) $page = $count;

        $start = ( $page - 1 ) * $per;
        $start = $start ? $start : $defaultStart;

        $ORDER = isset( $sort[ 'default' ] ) ? ' ORDER BY ' . $sort[ 'default' ] . ' DESC ' : '';

        $query = $SELECT . ' ' . $baseQuery . " $ORDER LIMIT $start , $per ; ";

        $data = DB ::select( $query , $bindings );

        $result = [ 'data' => $data , 'all' => $all , 'page' => $page , 'per' => $per ];

        if( env( 'APP_DEBUG' ) )
        {
            $result[ 'query' ] = $query;

            $result[ 'countQuery' ] = $allQuery;
        }

        return $result;
    }

    protected function returnList( Request $request , $SELECT , $FROM , $WHERE , array $filter , array $sort = [] , $groupBy = '' , $having = '' , $havingClause = '' , $start = 0 )
    {
        return response() -> json( array_merge( [ 'status' => 'success' ] , $this -> configureList( $request , $SELECT , $FROM , $WHERE , $filter , $sort , $groupBy , $having , $havingClause , $start ) ) );
    }


    public static function menu()
    {
        return view( 'admin.menu' , [ 'roles' => [] ] );
    }


    public function clear( $parameter )
    {
        return trim( str_replace( [ "'" , '"' , "`" ] , [ "\'" , '\"' , "" ] , $parameter ) );
    }


    public static function LANG_PARAM( $param = 'name' , $table = '' , $as = true )
    {
        return ( strlen( $table ) ? ( $table . '.' ) : '' ) . $param . '_' . App ::getLocale() . ( $as ? ( ' AS ' . $param ) : '' );
    }


    public static function _DATE( $date , $month_ = false , $short = false , $hour = false )
    {
        $monthArray = [
            'az' => [
                1  => 'Yanvar' ,
                2  => 'Fevral' ,
                3  => 'Mart' ,
                4  => 'Aprel' ,
                5  => 'May' ,
                6  => 'İyun' ,
                7  => 'İyul' ,
                8  => 'Avqust' ,
                9  => 'Sentyabr' ,
                10 => 'Oktyabr' ,
                11 => 'Noyabr' ,
                12 => 'Dekabr'
            ] ,
            'en' => [
                1  => 'January' ,
                2  => 'February' ,
                3  => 'March' ,
                4  => 'April' ,
                5  => 'May' ,
                6  => 'June' ,
                7  => 'July' ,
                8  => 'August' ,
                9  => 'September' ,
                10 => 'October' ,
                11 => 'November' ,
                12 => 'December'
            ] ,
            'ru' => [
                1  => 'январь' ,
                2  => 'февраль' ,
                3  => 'март' ,
                4  => 'апрель' ,
                5  => 'май' ,
                6  => 'июнь' ,
                7  => 'июль' ,
                8  => 'август' ,
                9  => 'сентябрь' ,
                10 => 'октября' ,
                11 => 'ноябрь' ,
                12 => 'Декабрь'
            ]
        ];

        $shortMonthArray = [
            'az' => [
                1  => 'Yan' ,
                2  => 'Fev' ,
                3  => 'Mart' ,
                4  => 'Apr' ,
                5  => 'May' ,
                6  => 'İyun' ,
                7  => 'İyul' ,
                8  => 'Avq' ,
                9  => 'Sen' ,
                10 => 'Okt' ,
                11 => 'Noy' ,
                12 => 'Dek'
            ] ,
            'en' => [
                1  => 'Jan' ,
                2  => 'Feb' ,
                3  => 'March' ,
                4  => 'Apr' ,
                5  => 'May' ,
                6  => 'June' ,
                7  => 'July' ,
                8  => 'Aug' ,
                9  => 'Sep' ,
                10 => 'Oct' ,
                11 => 'Nov' ,
                12 => 'Dec'
            ] ,
            'ru' => [
                1  => 'янв' ,
                2  => 'фев' ,
                3  => 'март' ,
                4  => 'апр' ,
                5  => 'май' ,
                6  => 'июнь' ,
                7  => 'июль' ,
                8  => 'авг' ,
                9  => 'сен' ,
                10 => 'окт' ,
                11 => 'ноя' ,
                12 => 'Дек'
            ]
        ];

        $date = date( 'Y-m-d H:i' , strtotime( $date ) );

        $month = $monthArray[ App ::getLocale() ][ (int)date( 'm' , strtotime( $date ) ) ];

        $shortMonth = $shortMonthArray[ App ::getLocale() ][ (int)date( 'm' , strtotime( $date ) ) ];

        if( $month_ )
        {
            if( $short ) return $shortMonth;

            return $month;
        }

        $day = (int)date( 'd' , strtotime( $date ) );

        $year = (int)date( 'Y' , strtotime( $date ) );

        return $day . ' ' . $month . ' ' . $year . ( $hour ? date( ' H:i' , strtotime( $date ) ) : '' );
    }


    protected function avatar( $photo , $type = 'post' )
    {
        return self ::_AVATAR( $photo , $type );
    }


    public static function _AVATAR( $photo , $type = 'post' )
    {
        $photo = explode( '.' , $photo );

        $name = $photo[ 0 ] ?? '';
        $ext  = $photo[ 1 ] ?? '';

        return media( $type . '/' . $name . '_avatar.' . $ext );
    }
}
