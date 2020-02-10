@php
    $currentRoute = Route::currentRouteName();
@endphp

@section('js')

    @if( strpos( $currentRoute , 'admin.' ) !== false )

        <!--jQuery -->
        <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>

        <!--begin::Base Scripts -->
        <script src='{{ asset('admin/js/vendors.bundle.js') }}'></script>
        <script src='{{ asset('admin/js/scripts.bundle.js') }}'></script>
        <!--end::Base Scripts -->

        <script src='{{ asset('admin/js/bootstrap-tagsinput.min.js') }}'></script>

    @endif

    <!--Js Cookie -->
    <script src='{{ asset('admin/js/js.cookie.min.js') }}'></script>

    <!--SweetAlert2 -->
    <script src='{{ asset('admin/js/sweetalert2.all.min.js') }}'></script>

    <!--Underscore -->
    <script src='{{ asset('admin/js/underscore-min.js') }}'></script>


    <script type="text/javascript">
        _.templateSettings.variable = "rc";

        let locale = '{{ app() -> getLocale() }}' ,
            csrf = '{{ csrf_token() }}' ,
            deleted_files = [];


        function loading( state = 0 )
        {
            if( state )
            {
                $( '#loading' ).show();

                $( 'body' ).addClass( 'loading-progress' );
            } else
            {
                $( '#loading' ).hide();

                $( 'body' ).removeClass( 'loading-progress' );
            }
        }


        function error( message = '' , title = "{{ __('message.SomethingWentWrong') }}" )
        {
            const swalWithBootstrapButtons = swal.mixin( {
                confirmButtonClass : 'btn btn-primary' ,
                buttonsStyling : false ,
            } );

            swalWithBootstrapButtons( {
                title : title ,
                text : message ,
                type : 'warning'
            } );

            loading();
        }


        function init()
        {
            @if( strpos( $currentRoute , 'admin.' ) !== false )

            $( '[x-datetime]' ).each( function()
            {
                $( this ).datetimepicker( {
                    todayHighlight : true ,
                    pickerPosition : 'bottom-left' ,
                    autoclose : true ,
                    todayBtn : true ,
                    format : 'yyyy-mm-dd hh:ii:ss' ,
                    weekStart : 1 ,
                    locale : locale
                } );
            } );

            $( '[x-date]' ).each( function()
            {
                let t = $( this ) , weekday = t.attr( 'x-weekday' ) ,
                    $disabledWeekDays = weekday != undefined ? [ 0 , 1 , 2 , 3 , 4 , 5 , 6 ] : [];

                if( weekday != undefined )
                {
                    $disabledWeekDays = jQuery.grep( $disabledWeekDays , function( value )
                    {
                        return value != weekday;
                    } );
                }

                $( this ).datetimepicker( {
                    todayHighlight : true ,
                    pickerPosition : 'bottom-left' ,
                    autoclose : true ,
                    todayBtn : true ,
                    format : 'yyyy-mm-dd' ,
                    startView : 2 , minView : 2 , forceParse : 0 ,
                    weekStart : 1 ,
                    daysOfWeekDisabled : $disabledWeekDays ,
                    locale : locale
                } );
            } );

            $( '[x-select]' ).selectpicker();

            $( '[x-select-2]' ).select2();

            $( '[x-select-2-min]' ).select2( { minimumResultsForSearch : -1 } );

            $( '[x-select-2-url]' ).each( function()
            {
                let t = $( this ) , url = t.attr( 'x-select-2-url' ) , query = { _token : csrf } ,
                    column = t.attr( 'x-data-column' ) !== undefined ? t.attr( 'x-data-column' ) : false ,
                    value = t.attr( 'x-data-value' ) !== undefined ? t.attr( 'x-data-value' ) : null;

                if( column ) query[ [ column ] ] = value;

                let column2 = t.attr( 'x-data-column2' ) !== undefined ? t.attr( 'x-data-column2' ) : false ,
                    value2 = t.attr( 'x-data-value2' ) !== undefined ? t.attr( 'x-data-value2' ) : null;
                if( column2 ) query[ [ column2 ] ] = value2;

                t.select2( {
                    ajax : {
                        url : url ,
                        type : 'POST' ,
                        data : function( params )
                        {
                            query.search = params.term;

                            return query;
                        } ,
                        processResults : function( data )
                        {
                            return {
                                results : data.data
                            };
                        }
                    }
                } );
            } );

            $( '[x-summernote]' ).each( function()
            {
                let name = $( this ).attr( 'name' ) ,
                    height = name == 'note_en' || name == 'note_az' || name == 'note_ru' ? 100 : 222;

                $( this ).summernote( { height : height } );
            } );

            $( '[x-tagsinput]' ).each( function()
            {
                // $( this ).tagsinput();
            } );

            $( '.modal' ).on( 'hidden.bs.modal' , function()
            {
                setTimeout( function()
                {
                    if( $( '.modal' ).length ) $( 'body' ).addClass( 'modal-open' );
                    else $( 'body' ).removeClass( 'modal-open' );

                    deleted_files = [];
                } , 5 );
            } );

            @endif

            loading();
        }


        function createModal( title , body , close = true , widthClass = 'my-col-xs-11 my-col-sm-10 my-col-md-9 my-col-lg-8' , buttons = '' , headerClass = '' )
        {
            var headerClass = typeof headerClass === "undefined" ? "" : headerClass ,
                widthClass = typeof widthClass === "undefined" ? "" : widthClass ,
                buttons = typeof buttons === "undefined" ? "" : buttons ,
                modalNumber = createModalHTML( widthClass , close );

            var m = $( "#newModal" + modalNumber );
            m.find( ".modal-content" ).children( ":not([do=loading] , .modal-header:eq(0) , .modal-body:eq(0) , .modal-footer:eq(0))" ).remove();
            if( m.find( ".modal-content" ).children( ".modal-body" ).length == 0 )
            {
                m.find( ".modal-content" ).append( '<div class="modal-body"></div>' );
            }
            if( m.find( ".modal-content" ).children( ".modal-footer" ).length == 0 )
            {
                //m.find( ".modal-content" ).append( '<div class="modal-footer"></div>' );
            }
            m.find( ".modal-header" ).addClass( headerClass );
            m.find( ".modal-title" ).html( title );
            m.find( ".modal-body-content" ).html( body );
            m.find( ".modal-footer" ).html( buttons );
            m.css( 'cssText' , 'z-index: ' + ( 12 + modalNumber * 2 ) + ' !important; top: 50px !important; margin-left: 77px;' );
            m.modal( "show" );
            m.next( '.modal-backdrop' ).css( 'cssText' , 'z-index: ' + ( 11 + modalNumber * 2 ) + ' !important' );

            if( m.find( '[x-modal-title]' ) ) m.find( '.modal-title' ).html( m.find( '[x-modal-title]' ).attr( 'x-modal-title' ) );

            return m;
        }

        function createModalHTML( widthClass , close )
        {
            var n = 1;
            while( $( "#newModal" + n ).length ) n++;
            var effects = [ "flipInX" , "flipInY" , "pulse" , "jello" , "fadeIn" ] ,
                effect = effects[ Math.floor( Math.random() * 111 ) % effects.length ];
            $( "body" ).append( '<div class="modal scroll fade" id="newModal' + n + '" role="dialog" ' + ( close ? '' : 'data-backdrop="static"' ) + '><div class="modal-dialog ' + effect + ' ' + widthClass + ' animated"><div class="modal-content"><div class = "modal-header" style = "width: 100%; position: relative; padding: 8px;"><h4 class = "modal-title" style="font-size: 21px; margin-left: 10px;"></h4><span class="close-modal" data-dismiss="modal" aria-label="Close" style="position:absolute; top: 9px; right: 9px; cursor: pointer;"><i class="la la-close" style="font-size: 30px; font-weight: bold;"></i></span></div><div class="modal-body scroll" style="max-height: ' + ( window.innerHeight - 185 ) + 'px;"><div class="row modal-body-content"></div></div></div></div></div></div>' );
            $( "#newModal" + n ).on( "hidden.bs.modal" , function()
            {
                $( this ).remove();
            } );
            return n;
        }


        function pagination( page , all , per , length , list = 'main' )
        {
            let show = 3 , count = Math.ceil( all / per ) , html = '';

            if( all )
            {
                html += '<ul class="m-datatable__pager-nav"><h5 style="color: #ffc425;">Total: ' + all + '</h5></ul>';
            }

            if( count > 1 )
            {
                html += '<ul class="m-datatable__pager-nav pull-right" style="margin-top: 5px; margin-left: 30px;">';

                if( page > 1 )
                {
                    html += '<li><a x-paginate="' + ( page - 1 ) + '" class="m-datatable__pager-link"><i class="la la-angle-left"></i></a></li>';
                }

                if( page > ( show + 1 ) )
                {
                    html += '<li><a x-paginate="1" class="m-datatable__pager-link">1</a></li>';

                    if( page > ( show + 2 ) )
                    {
                        html += '<li><a>. . .</a></li>';
                    }
                }

                for( let i = 1; i <= count; i++ )
                {
                    if( ( page > i && ( page - i ) <= show ) || ( page < i && ( i - page ) <= show ) || i === page )
                    {
                        html += '<li><a ' + ( i !== page ? 'x-paginate="' + i + '"' : '' ) + ' class="m-datatable__pager-link ' + ( i === page ? 'm-datatable__pager-link--active' : '' ) + '">' + i + '</a></li>';
                    }
                }

                if( ( count - page ) > show )
                {
                    if( ( count - page ) > ( show + 1 ) )
                    {
                        html += '<li><a>. . .</a></li>';
                    }

                    html += '<li><a x-paginate="' + count + '" class="m-datatable__pager-link">' + count + '</a></li>';
                }

                if( page < count )
                {
                    html += '<li><a x-paginate="' + ( page + 1 ) + '" class="m-datatable__pager-link"><i class="la la-angle-right"></i></a></li>';
                }
            }

            $( '[x-pagination="' + list + '"]' ).html( html );


            if( ( page - 1 ) * per + length < all ) $( '[x-more]' ).show();
            else $( '[x-more]' ).hide();
        }


        function checkboxList()
        {
            $( '[x-checkbox-list]' ).each( function()
            {
                let t = $( this ) ,
                    name = t.attr( 'x-checkbox-list' ) ,
                    f = t.closest( 'form' ) ,
                    parameters = [];

                if( t.find( '[x-checkbox-id]' ).length )
                {
                    t.find( '[x-checkbox-id]' ).each( function()
                    {
                        let i = $( this ) , id = i.attr( 'x-checkbox-id' );

                        if( id != undefined && i.prop( 'checked' ) )
                        {
                            parameters.push( id );
                        }
                    } );
                }

                if( name != undefined )
                {
                    if( name === 'roles' && t.find( '[x-checkbox-id="admin"]' ).prop( 'checked' ) )
                    {
                        f.find( '[name="' + name + '"]' ).val( 'admin' );
                    } else
                    {
                        f.find( '[name="' + name + '"]' ).val( parameters );
                    }
                }
            } );
        }


        function media( url )
        {
            try
            {
                let http = new XMLHttpRequest();
                http.open( 'HEAD' , url , false );
                http.send();
                return http.status < 300 ? url : '{{ media('') }}';
            } catch( e )
            {
                return '{{ media('') }}';
            }
        }


        function images( data )
        {
            if( data.images !== undefined && data.images.length )
            {
                for( let i in data.images )
                {
                    $( '[x-images]' ).append( _.template( $( 'script[x-image]' ).html() )( data.images[ i ] ) );
                }
            }
        }


        $( document ).ready( function()
        {
            $( '[x-list-form="main"]' ).append( '<input type="hidden" name="page" value="1">' );

            if( $( '[x-service]' ).length ) $( '[x-list-form]' ).append( '<input type="hidden" name="service" value="{{ request() -> route() && request() -> route() -> parameter('service') ? request()->route()->parameter('service') : 'All' }}">' );

            @if($currentRoute != 'admin.translation.page')
            $( '[x-list-form="main"]' ).append( '<input type="hidden" name="per" value="10"><input type="hidden" name="search" value="">' );
            @endif


            $( document ).on( 'submit' , '[x-list-form]' , function( e )
            {
                e.preventDefault();

                loading( 1 );

                let t = $( this ) , formData = new FormData( this ) , html = '' , body = t.find( '[x-list-tbody]' ) ,
                    list = t.attr( 'x-list-form' ) ,
                    modal = t.attr( 'x-modal' ) !== undefined ? t.attr( 'x-modal' ) : 'tr';

                if( !body.length ) body = $( '[x-list-tbody]' );

                formData.append( '_token' , csrf );

                $.ajax( {
                    type : 'POST' ,
                    url : t.attr( 'action' ) ,
                    data : formData ,
                    cache : false ,
                    contentType : false ,
                    processData : false ,
                    success : function( res )
                    {
                        if(
                            res &&
                            typeof ( res[ 'data' ] ) !== 'undefined' &&
                            typeof ( res[ 'all' ] ) !== 'undefined' &&
                            typeof ( res[ 'page' ] ) !== 'undefined' &&
                            typeof ( res[ 'per' ] ) !== 'undefined'
                        )
                        {
                            let data = res[ 'data' ] ,
                                all = res[ 'all' ] ,
                                page = res[ 'page' ] ,
                                per = res[ 'per' ];

                            if( data.length )
                            {
                                var k = 1;

                                for( let i in data )
                                {
                                    data[ i ][ 'no' ] = k++;

                                    html += _.template( $( 'script[x-' + modal + ']' ).html() )( data[ i ] );
                                }
                            } else
                            {
                                html = '<tr><td colspan="55" style="text-align: center;"><h4>No data found!</h4></td></tr>';
                            }

                            pagination( page , all , per , data.length , list );

                            body.html( html );

                            @if( $currentRoute !== 'admin.dashboard' )
                            body.closest( '.scroll' ).animate( { scrollTop : 0 } , 'slow' );
                            @endif

                            loading();
                        } else
                        {
                            error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                        }
                    }
                } );
            } );


            $( document ).on( 'click' , '[x-paginate]' , function()
            {
                let page = $( this ).attr( 'x-paginate' ) ,
                    list = $( this ).closest( '[x-pagination]' ).attr( 'x-pagination' );

                $( '[x-list-form="' + list + '"] [name="page"]' ).val( page );

                $( '[x-list-form="' + list + '"]' ).submit();
            } );


            $( document ).on( 'click' , '[x-more]' , function()
            {
                let t = $( this ) ,
                    form = t.closest( 'form' ) ,
                    input = form.find( '[name="page"]' ) ,
                    page = input.val();

                input.val( ++page );

                form.submit();
            } );


            $( document ).on( 'click' , '[x-service]' , function( e )
            {
                e.stopPropagation();
                e.preventDefault();

                let t = $( this ) ,
                    form = t.closest( 'form' ) ,
                    input = form.find( '[name="service"]' ) ,
                    service = t.attr( 'x-service' );

                input.val( service );

                $( '.filter li' ).not( this ).removeClass( 'active' );
                $( this ).addClass( 'active' );

                form.submit();
            } );


            $( document ).on( 'change' , '[x-per]' , function()
            {
                let per = $( this ).val();

                $( '[x-list-form="main"] [name="per"]' ).val( per );

                $( '[x-list-form="main"]' ).submit();
            } );


            $( document ).on( 'change' , '[x-search]' , function()
            {
                let search = $( this ).val();

                $( '[x-list-form="main"] [name="search"]' ).val( search );

                $( '[x-list-form="main"]' ).submit();
            } );


            $( document ).on( 'change' , '[x-list-form] select' , function()
            {
                if( $( this ).attr( 'x-no-submit' ) === undefined ) $( this ).closest( '[x-list-form]' ).submit();
            } );


            $( document ).on( 'change' , '[x-list-form] input' , function()
            {
                if( $( this ).attr( 'x-activate-url' ) === undefined && $( this ).attr( 'x-no-submit' ) === undefined ) $( this ).closest( '[x-list-form]' ).submit();
            } );


            $( document ).on( 'click' , '[x-edit-url]' , function( e )
            {
                e.preventDefault();
                e.stopPropagation();

                loading( 1 );

                let t = $( this ) , tr = t.closest( '[x-tr-id]' ) ,
                    id = tr.attr( 'x-tr-id' ) , xId = t.attr( 'x-modal-id' ) ,
                    url = t.attr( 'x-edit-url' ) ,
                    _modal = t.attr( 'x-modal' ) !== undefined ? t.attr( 'x-modal' ) : 'edit-modal' ,
                    list = t.closest( '[x-list-form]' ).attr( 'x-list-form' );

                id = xId !== undefined ? xId : id;

                $.post( url , {
                    'id' : id ,
                    '_token' : csrf
                } ).done( function( res )
                {
                    if( res[ 'status' ] === 'success' )
                    {
                        res[ 'data' ][ 'x_list_form' ] = list;

                        let info = _.template( $( 'script[x-' + _modal + ']' ).html() )( res[ 'data' ] ) ,
                            modal = createModal( 'Edit' , '<div class="modal-body">' + info + '</div>' , false );

                        init();

                        setTimeout( function()
                        {
                            if( res.data.service ) modal.find( '[name="service"]' ).val( res.data.service ).trigger( 'change' );
                            if( res.data.service_id ) modal.find( '[name="service"]' ).val( res.data.service_id ).trigger( 'change' );
                            if( res.data.parent_id ) modal.find( '[name="parent"]' ).val( res.data.parent_id ).trigger( 'change' );
                            if( res.data.type_id ) modal.find( '[name="type"]' ).val( res.data.type_id ).trigger( 'change' );

                            $( '[x-checkbox-list]' ).each( function()
                            {
                                var t = $( this ) , name = t.attr( 'x-checkbox-list' ) ,
                                    max = t.attr( 'x-checkbox-max' );

                                if( res.data[ name ] != undefined && res.data[ name ].length )
                                {
                                    if( $.type( res.data[ name ] ) == 'string' )
                                    {
                                        let variants = res.data[ name ].split( ',' );

                                        if( name === 'roles' && variants == 'admin' )
                                        {
                                            $( '[x-checkbox-list="' + name + '"] [x-checkbox-id]' ).prop( 'checked' , true );
                                        } else if( variants.length )
                                        {
                                            $.each( variants , function( k , variant )
                                            {
                                                $( '[x-checkbox-list="' + name + '"] [x-checkbox-id="' + variant + '"]' ).prop( 'checked' , true );
                                            } );
                                        }

                                        if( max !== undefined && variants.length >= max )
                                        {
                                            $( '[x-checkbox-list="' + name + '"] [x-checkbox-id]:not(:checked)' ).prop( 'disabled' , true );
                                            $( '[x-checkbox-list="' + name + '"] [x-checkbox-id]:not(:checked)' ).closest( 'label' ).addClass( 'disabled' );
                                        }
                                    }
                                }
                            } );

                            new mPortlet( 'm_portlet_tools_role' );

                            setTimeout( function()
                            {
                                if( $.isFunction( window.campaignActivities ) ) campaignActivities( res.data );

                                checkboxList();
                            } , 55 );
                        } , 5 );
                    } else
                    {
                        error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                    }
                } ).fail( function()
                {
                    error( 'Network error!' );
                } );
            } );


            $( document ).on( 'click' , '[x-add]' , function( e )
            {
                e.preventDefault();
                e.stopPropagation();

                loading( 1 );

                let t = $( this ) , _modal = t.attr( 'x-modal' ) !== undefined ? t.attr( 'x-modal' ) : 'edit-modal' ,
                    list = t.closest( '[x-list-form]' ).attr( 'x-list-form' ) ,
                    column = t.attr( 'x-data-column' ) !== undefined ? t.attr( 'x-data-column' ) : false ,
                    value = t.attr( 'x-data-value' ) !== undefined ? t.attr( 'x-data-value' ) : null ,
                    data = { x_list_form : list };

                if( column ) data[ [ column ] ] = value;

                let info = _.template( $( 'script[x-' + _modal + ']' ).html() )( data ) ,
                    modal = createModal( 'Add' , '<div class="modal-body">' + info + '</div>' , false );

                init();

                setTimeout( function()
                {
                    new mPortlet( 'm_portlet_tools_role' );

                    if( $.isFunction( window.addDependency ) ) addDependency();
                } , 5 );
            } );


            $( document ).on( 'submit' , '[x-edit-form]' , function( e )
            {
                e.preventDefault();

                let submitButton = $( this ).find( '[type="submit"]' );
                // submitButton.attr( 'disabled' , 'disabled' );

                loading( 1 );

                let t = $( this ) , formData = new FormData( this ) , url = t.attr( 'action' ) ,
                    list = t.attr( 'x-list' ) !== undefined ? t.attr( 'x-list' ) : 'main' ,
                    target = t.attr( 'x-target' );

                t.find( '[name]' ).each( function()
                {
                    let _t = $( this ) , name = _t.attr( 'name' );

                    _t.css( 'border-color' , '#ebedf2' );
                    _t.prev( 'label' ).css( 'color' , '#575962' );
                } );

                t.find( '[for]' ).css( 'color' , '#575962' );
                t.find( '[x-tab-title]' ).css( 'color' , '#575962' );

                formData.append( '_token' , csrf );

                $.ajax( {
                    type : 'POST' ,
                    url : url ,
                    data : formData ,
                    cache : false ,
                    contentType : false ,
                    processData : false ,
                    success : function( res )
                    {
                        if( res[ 'status' ] === 'success' )
                        {
                            if( res.validations !== undefined && Object.keys( res.validations ).length )
                            {
                                $.each( res.validations , function( name , v )
                                {
                                    let tab = t.find( '[name="' + name + '"]' ).closest( '[x-tab]' ).attr( 'id' );

                                    t.find( '[name="' + name + '"]' ).css( 'border-color' , 'red' );
                                    t.find( '[name="' + name + '"]' ).prev( 'label' ).css( 'color' , 'red' );
                                    t.find( '[for="' + name + '"]' ).css( 'color' , 'red' );

                                    t.find( '[x-tab-title][href="#' + tab + '"]' ).css( 'color' , 'red' );
                                } );

                                submitButton.removeAttr( 'disabled' );
                            } else
                            {
                                let title = res.title !== undefined ? res.title : 'Success!' ,
                                    text = res.text !== undefined ? res.text : '';

                                if( target && typeof window[ target ] === 'function' )
                                {
                                    window[ target ]();
                                } else if( $.isFunction( window.editFormCallback ) ) editFormCallback( res );

                                else
                                {
                                    const swalWithBootstrapButtons = swal.mixin( {
                                        confirmButtonClass : 'btn btn-primary' ,
                                        buttonsStyling : false
                                    } );

                                    swalWithBootstrapButtons( {
                                        type : 'success' ,
                                        title : title ,
                                        text : text ,
                                        showConfirmButton : true
                                    } );

                                    if( t.attr( 'x-redirect-url' ) !== undefined )
                                    {
                                        location.href = t.attr( 'x-redirect-url' );
                                    } else if( t.closest( '.modal' ).length )
                                    {
                                        $( '[x-list-form="' + list + '"]' ).submit();

                                        t.closest( '.modal' ).modal( 'hide' );
                                    } else
                                    {
                                        //
                                    }
                                }
                            }

                            loading();
                        } else
                        {
                            error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                        }
                    } ,
                    error : function( res )
                    {
                        error( 'Network error!' );
                    }
                } );
            } );


            $( document ).on( 'click' , '[x-view-url]' , function( e )
            {
                e.preventDefault();
                e.stopPropagation();

                loading( 1 );

                let t = $( this ) , tr = t.closest( '[x-tr-id]' ) , id = tr.attr( 'x-tr-id' ) ,
                    url = t.attr( 'x-view-url' ) ,
                    column = t.attr( 'x-modal-column' ) , xId = t.attr( 'x-modal-id' ) ,
                    modal_ = t.attr( 'x-modal' );

                column = column !== undefined ? column : 'id';

                id = xId !== undefined ? xId : id;

                modal_ = modal_ !== undefined ? modal_ : 'x-view-modal';

                $.post( url , {
                    [ column ] : id ,
                    '_token' : csrf
                } ).done( function( res )
                {
                    if( res[ 'status' ] === 'success' )
                    {
                        var info = _.template( $( 'script[' + modal_ + ']' ).html() )( res[ 'data' ] ) ,
                            modal = createModal( 'Info' , '<div class="modal-body">' + info + '</div>' );

                        loading();
                    } else
                    {
                        error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                    }
                } ).fail( function()
                {
                    error( 'Network error!' );
                } );
            } );


            $( document ).on( 'click' , '[x-modal-url]' , function( e )
            {
                e.preventDefault();
                e.stopPropagation();

                loading( 1 );

                let t = $( this ) , tr = t.closest( '[x-tr-id]' ) ,
                    id = t.attr( 'x-modal-id' ) !== undefined ? t.attr( 'x-modal-id' ) : tr.attr( 'x-tr-id' ) ,
                    url = t.attr( 'x-modal-url' ) , column = t.attr( 'x-modal-column' );

                column = column !== undefined ? column : 'id';

                $.post( url , {
                    [ column ] : id ,
                    '_token' : csrf
                } ).done( function( res )
                {
                    if( res[ 'status' ] === 'success' )
                    {
                        modal = createModal( 'Info' , '<div class="modal-body">' + res[ 'data' ] + '</div>' );

                        setTimeout( function()
                        {
                            modal.find( '[x-list-form]' ).submit();
                        } , 5 );

                        init();
                    } else
                    {
                        error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                    }
                } ).fail( function()
                {
                    error( 'Network error!' );
                } );
            } );


            $( document ).on( 'change' , '[x-activate-url]' , function( e )
            {
                e.stopPropagation();

                loading( 1 );

                let t = $( this ) , url = t.attr( 'x-activate-url' ) , xId = t.attr( 'x-modal-id' ) ,
                    id = t.closest( '[x-tr-id]' ).attr( 'x-tr-id' ) ,
                    active = Number( t.prop( 'checked' ) ) , column = t.attr( 'x-modal-column' );

                id = xId !== undefined ? xId : id;

                column = column !== undefined ? column : 'active';

                $.post( url , {
                    'id' : id ,
                    '_token' : csrf ,
                    [ column ] : active
                } ).done( function( res )
                {
                    if( res[ 'status' ] === 'success' )
                    {
                        loading();
                    } else
                    {
                        error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                    }
                } ).fail( function()
                {
                    error( 'Network error!' );
                } );
            } );

            $( document ).on( 'change' , '[x-activate-url-refresh]' , function( e )
            {
                e.stopPropagation();

                loading( 1 );

                let t = $( this ) , url = t.attr( 'x-activate-url-refresh' ) , xId = t.attr( 'x-modal-id' ) ,
                    id = t.closest( '[x-tr-id]' ).attr( 'x-tr-id' ) ,
                    active = Number( t.prop( 'checked' ) ) , column = t.attr( 'x-modal-column' );

                id = xId !== undefined ? xId : id;

                column = column !== undefined ? column : 'active';

                $.post( url , {
                    'id' : id ,
                    '_token' : csrf ,
                    [ column ] : active
                } ).done( function( res )
                {
                    if( res[ 'status' ] === 'success' )
                    {
                        loading();
                        $('[x-list-form]').trigger('submit');
                    } else
                    {
                        error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                    }
                } ).fail( function()
                {
                    error( 'Network error!' );
                } );
            } );


            $( document ).on( 'change' , '[x-change-url]' , function( e )
            {
                e.stopPropagation();

                loading( 1 );

                let t = $( this ) , url = t.attr( 'x-change-url' ) , id = t.closest( '[x-tr-id]' ).attr( 'x-tr-id' ) ,
                    value = t.val() , column = t.attr( 'x-modal-column' );

                column = column !== undefined ? column : 'status';

                $.post( url , {
                    'id' : id ,
                    '_token' : csrf ,
                    [ column ] : value
                } ).done( function( res )
                {
                    if( res[ 'status' ] === 'success' )
                    {
                        loading();
                    } else
                    {
                        error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                    }
                } ).fail( function()
                {
                    error( 'Network error!' );
                } );
            } );


            $( document ).on( 'click' , '[x-checkbox-id]' , function()
            {
                let t = $( this ) , name = t.attr( 'x-checkbox-id' ) ,
                    p = t.closest( '[x-checkbox-list]' ) , p_name = p.attr( 'x-checkbox-list' ) ,
                    max = p.attr( 'x-checkbox-max' ) ,
                    group = t.closest( '[x-checkbox-group="' + name + '"]' ) ,
                    state = t.prop( 'checked' )
                ;

                if( p_name === 'roles' )
                {
                    if( name === 'admin' ) p.find( '[x-checkbox-id]' ).prop( 'checked' , state );

                    else if( !state ) p.find( '[x-checkbox-id="admin"]' ).prop( 'checked' , false );

                    else if( p.find( '[x-checkbox-id]:checked' ).length + 1 === p.find( '[x-checkbox-id]' ).length ) p.find( '[x-checkbox-id="admin"]' ).prop( 'checked' , true );
                }

                if( max !== undefined )
                {
                    if( p.find( '[x-checkbox-id]:checked' ).length >= max )
                    {
                        p.find( '[x-checkbox-id]:not(:checked)' ).prop( 'disabled' , 'disabled' );

                        p.find( '[x-checkbox-id]:not(:checked)' ).closest( 'label' ).addClass( 'disabled' );
                    } else
                    {
                        p.find( '[x-checkbox-id]' ).prop( 'disabled' , '' );

                        p.find( '[x-checkbox-id]:not(:checked)' ).closest( 'label' ).removeClass( 'disabled' );
                    }
                }

                if( group !== undefined && group.length && group.find( '[x-checkbox-id]' ).length ) group.find( '[x-checkbox-id]' ).prop( 'checked' , state );

                t.parents( '[x-checkbox-group]' ).each( function()
                {
                    let _t = $( this ) , _name = _t.attr( 'x-checkbox-group' );

                    if( !state || ( _t.find( '[x-checkbox-id]:checked' ).length + 1 === _t.find( '[x-checkbox-id]' ).length && _name !== 'admin' ) ) $( '[x-checkbox-id="' + _name + '"]' ).prop( 'checked' , state );
                } );

                setTimeout( function()
                {
                    checkboxList();
                } , 5 );
            } );


            $( document ).on( 'click' , '[x-delete-url]' , function( e )
            {
                let t = $( this ) , tr = t.closest( '[x-tr-id]' ) , url = t.attr( 'x-delete-url' ) ,
                    _with = t.attr( 'x-with' ) , _with_id = t.attr( 'x-with-id' ) ,
                    id = tr.attr( 'x-tr-id' );


                const swalWithBootstrapButtons = swal.mixin( {
                    confirmButtonClass : 'btn btn-dark' ,
                    cancelButtonClass : 'btn btn-primary' ,
                    buttonsStyling : false ,
                } );

                swalWithBootstrapButtons( {
                    title : "{{ __('message.SureToDelete') }}?" ,
                    type : 'warning' ,
                    showCancelButton : true ,
                    confirmButtonText : "{{ __('message.Yes') }}" ,
                    cancelButtonText : "{{ __('message.Cancel') }}" ,
                    reverseButtons : true
                } ).then( ( r ) =>
                {
                    if( r.value )
                    {
                        loading( 1 );

                        $.post( url , {
                            'id' : id ,
                            [ _with ] : _with_id ,
                            '_token' : csrf
                        } ).done( function( res )
                        {
                            if( res[ 'status' ] === 'success' )
                            {
                                tr.fadeOut( function()
                                {
                                    $( this ).remove();
                                } );

                                if( $.isFunction( window.__u ) ) __u();

                                loading();
                            } else
                            {
                                error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                            }
                        } ).fail( function()
                        {
                            error( 'Network error!' );
                        } );
                    }
                } );
            } );


            $( document ).on( 'keydown' , '[x-no-enter]' , function( e )
            {
                if( e.keyCode === 13 )
                {
                    e.preventDefault();
                    return false;
                }
            } );


            $( 'body' ).on( 'change' , '[x-photo-input]' , function( e )
            {
                let ext = e.target.files[ 0 ][ 'name' ].replace( /^.*\./ , '' ).toLowerCase() ,
                    t = $( this ) , name = t.attr( 'x-photo-input' ) ,
                    img = t.closest( '[x-photo]' ).find( '[x-photo-img="' + name + '"]' );

                if( ext == 'jpeg' || ext == 'png' || ext == 'jpg' )
                {
                    img.attr( 'src' , URL.createObjectURL( e.target.files[ 0 ] ) );
                } else
                {
                    t.val( '' );

                    img.attr( 'src' , img.attr( 'x-photo-default' ) );

                    error( '' , 'Select valid image extension' );
                }
            } );


            $( document ).on( 'click' , '[x-image-id] [x-image-delete]' , function( e )
            {
                e.stopPropagation();
                e.preventDefault();

                let div = $( this ).closest( '[x-image-id]' ) , id = div.attr( 'x-image-id' );

                deleted_files.push( id );

                $( '[name="deleted_files"]' ).val( JSON.stringify( deleted_files ) );

                div.fadeOut( function()
                {
                    $( this ).remove();
                } );
            } );


            $( document ).on( 'click' , '[x-customer]' , function( e )
            {
                e.preventDefault();
                e.stopPropagation();

                let type = $( this ).attr( 'x-customer' );

                $( '[x-customer]' ).removeClass( 'active' );
                $( '[x-customer="individual"]' ).addClass( 'active' );
                // $( '[x-customer="' + type + '"]' ).addClass( 'active' );

                // $( '[x-customer-show]' ).addClass( 'hidden' );
                // $( '[x-customer-show][x-' + type + ']' ).removeClass( 'hidden' );
            } );


            $( document ).keyup( function( e )
            {
                if( e.keyCode == 27 ) $( '.close-modal' ).click();
            } );


            init();

            $( '[x-list-form="main"]' ).submit();
        } )
        ;
    </script>

@show
