@php
    $title = 'Translations';

    $active = 'en';

    foreach ( $locales as $locale )
    {
        if( \App\Http\Controllers\Admin\AdminController::CAN( "translation.$locale.view" ) )
        {
            $active = $locale; break;
        }
    }
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('content')

    <style>
        .m-content
        {
            overflow : hidden !important;
        }
    </style>

    <form action="{{ route( 'admin.translation.list' ) }}" method="post" x-list-form>
        <div class="row">
            <div class="col-sm-1">
                <select class="form-control m-input m-input--air" name="per" x-select>
                    <option>15</option>
                    <option>30</option>
                    <option>60</option>
                    <option>120</option>
                </select>
            </div>
            <div class="col-sm-4">
                <input type="text" class="form-control m-input m-input--air" name="search" placeholder="{{ __( 'message.Search' ) }}..." x-no-submit>
            </div>
            <div class="col-sm-3">
                <select class="form-control m-input m-input--air" name="module" x-select-2>
                    <option value="All">All pages</option>
                    @foreach( $modules as $module )
                        <option>{{ $module -> screen_url }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select class="form-control m-input m-input--air" name="status" x-select>
                    <option value="1">Uncompleted</option>
                    <option>All</option>
                </select>
            </div>
            <div class="col-sm-1">
                <select class="form-control m-input m-input--air" x-select name="lang" x-locale-tab>
                    <option value="0">All</option>
                    @foreach( $locales as $locale )
                        @if( \App\Http\Controllers\Admin\AdminController::CAN( "translation.$locale.edit" ) )
                            <option>{{ $locale }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row scroll" x-list-tbody style="height: calc(100vh - 225px);"></div>
    </form>

@endsection


@section('extra_js')

    <script>
        $( document ).ready( function()
        {
            $( document ).on( 'change' , '[x-locale-tab]' , function()
            {
                var locale = $( this ).val();

                $( '[x-lang-tab="' + locale + '"]' ).click();
            } );


            $( document ).on( 'change' , 'textarea[x-lang]' , function()
            {
                var t = $( this ) , locale = t.attr( 'x-lang' ) , translation = t.val() ,
                    tr = t.closest( '[x-tr-id]' ) , key = tr.attr( 'x-tr-id' ) ,
                    statusArea = tr.find( '[x-status-area]' ) ,
                    description = tr.find( '[x-description]' ) ,
                    tab = tr.find( '[x-lang-tab="' + locale + '"]' );

                $.post( '{{ route('admin.translation.edit') }}' , {
                    'key' : key ,
                    'locale' : locale ,
                    'translation' : translation ,
                    '_token' : csrf
                } ).done( function( res )
                {
                    if( res.status !== undefined )
                    {
                        if( res.status === 'success' )
                        {
                            if( !( res.validations !== undefined && Object.keys( res.validations ).length ) )
                            {
                                if( locale == '{{ $defaultLocale }}' ) description.val( translation );

                                $.notify( 'Successfully edited' , {
                                    type : 'success' ,
                                    allow_dismiss : true ,
                                    newest_on_top : 'top' ,
                                    mouse_over : true ,
                                    showProgressbar : false ,
                                    spacing : 10 ,
                                    timer : 2000 ,
                                    placement : {
                                        from : 'top' ,
                                        align : 'right'
                                    } ,
                                    offset : { x : 30 , y : 30 } ,
                                    delay : 1000 ,
                                    z_index : 10000 ,
                                    animate : {
                                        enter : "animated " + 'bounceInLeft' ,
                                        exit : "animated " + 'zoomOutDown'
                                    }
                                } );

                                tab.removeClass( 'tab-red' );

                                if( tr.find( '[x-lang-tab].tab-red' ).length !== undefined && tr.find( '[x-lang-tab].tab-red' ).length === 0 )
                                {
                                    statusArea.removeClass( 'm-portlet--danger' );

                                    if( $( '[name="status"]' ).val() == 1 ) $( '[x-list-form]' ).submit();
                                }
                            }
                        } else
                        {
                            error();
                        }
                    } else
                    {
                        error();
                    }
                } ).fail( function()
                {
                    error();
                } );
            } );
        } );
    </script>

@endsection


@section('templates')

    <script type="text/template" x-tr>
        <div class="col-md-4" x-tr-id="<%= rc.key %>" data-toggle="m-tooltip" title="Key: <%= rc.key %>  |  Page: <%= rc.page %>">
            <div x-status-area class="m-portlet <% if( rc.completed == false ){ %>m-portlet--danger<% } %> m-portlet--head-solid-bg">
                <div class="m-portlet__head" style="height: .5em !important;"></div>
                <div class="m-portlet__body" style="padding: 1em !important;">
                    <textarea rows="3" class="form-control m-input m-input--air scroll-2" disabled x-description><%= rc.description %></textarea>
                    <ul class="nav nav-tabs nav-fill" role="tablist" style="margin-top: 5px; margin-bottom: 5px !important;">
                        @foreach( $locales as $locale )
                            @if( \App\Http\Controllers\Admin\AdminController::CAN( "translation.$locale.view" ) )
                                <li class="nav-item">
                                    <a x-lang-tab="{{ $locale }}" class="nav-link @if( $locale == $active ) active @endif <% if( rc.translations.{{ $locale }}.completed == false ){ %>tab-red<% } %>" data-toggle="tab" href="#m_tabs_{{ $locale }}_<%= rc.key %>" style="padding: .2rem .5rem !important;">{{ $locale }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach( $locales as $locale )
                            @if( \App\Http\Controllers\Admin\AdminController::CAN( "translation.$locale.view" ) )
                                <div class="tab-pane @if( $locale == $active ) active @endif" id="m_tabs_{{ $locale }}_<%= rc.key %>" role="tabpanel">
                                    <textarea rows="3" x-lang="{{ $locale }}" class="form-control m-input m-input--air scroll-2" placeholder="{{ $locale }}" @if( ! \App\Http\Controllers\Admin\AdminController::CAN( "translation.$locale.edit" ) ) disabled @endif><%= rc.translations.{{ $locale }}.value %></textarea>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </script>

@endsection
