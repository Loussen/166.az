@php
    $title = 'Service inputs';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.service-input.list' ) }}" method="post" x-list-form="main">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Portlet-->
                <div class="m-portlet">
                    <div class="m-portlet__body">
                        <!--begin::Section-->
                        <div class="m-section">
                            <div class="m-section__content m_datatable m-datatable m-datatable--default m-datatable--loaded table-responsive">
                                <table class="table table-striped m-table m-table--head-separator-metal" x-list-table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Service</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="name" placeholder="Name">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="service" x-select-2>
                                                    <option>All</option>
                                                    @foreach( $services as $service )
                                                        <option value="{{ $service -> id }}">{{ $service -> name }}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="active" x-select>
                                                    <option>All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </th>
                                            <th>
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'serviceInput.add' ) )
                                                    <button type="button" class="btn m-btn btn-primary m-btn--icon m-btn--icon-only" x-add style="margin-bottom: 24px;">
                                                        <i class="la la-plus"></i>
                                                    </button>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody x-list-tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Section-->
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>
    </form>

@endsection


@section('extra_js')

    <script>
        let k = 0;

        function addCampaignActivity( activity = {} )
        {
            activity.k = k++;

            $( '[x-service-input-options]' ).append( _.template( $( 'script[x-service-input-option]' ).html() )( activity ) );
        }

        function campaignActivities( data )
        {
            if( data.options !== undefined && data.options.length )
            {
                for( let i in data.options )
                {
                    addCampaignActivity( data.options[ i ] );
                }
            }

            if( data.type !== undefined ) $( '[x-edit-form] [name="type"]' ).val( data.type ).trigger( 'change' );

            if( data.step !== undefined ) $( '[x-edit-form] [name="step"]' ).val( data.step ).trigger( 'change' );
        }

        $( document ).ready( function()
        {
            $( document ).on( 'click' , '[x-add-service-input-option]' , function()
            {
                addCampaignActivity();
            } );

            $( document ).on( 'click' , '[x-remove-service-input-option]' , function()
            {
                $( this ).closest( '[x-service-input-option-id]' ).fadeOut( function()
                {
                    $( this ).remove();
                } );
            } );

            $( document ).on( 'change' , '[x-options-show-hide]' , function()
            {
                $( '[x-coefficient]' ).addClass( 'hidden' );

                if( $( this ).val() == 'select' ) $( '[x-options]' ).removeClass( 'hidden' );
                else if($( this ).val() == 'number') $('[x-coefficient]').removeClass('hidden');
                else $( '[x-options]' ).addClass( 'hidden' );
            } );
        } );
    </script>

@endsection


@section('templates')

    <script type="text/template" x-tr>
        <tr x-tr-id="<%= rc.id %>">
            <td><%= rc.id %></td>
            <td><%= rc.name %></td>
            <td><%= rc.service %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.service-input.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'serviceInput.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'serviceInput.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.service-input.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'serviceInput.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.service-input.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.service-input.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Name ( EN )</label>
                            <input name="name_en" class="form-control m-input m-input--air" placeholder="Name ( EN )" value="<%= rc.name_en %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Name ( AZ )</label>
                            <input name="name_az" class="form-control m-input m-input--air" placeholder="Name ( AZ )" value="<%= rc.name_az %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Name ( RU )</label>
                            <input name="name_ru" class="form-control m-input m-input--air" placeholder="Name ( RU )" value="<%= rc.name_ru %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label for="service">Service</label>
                            <select name="service" class="form-control m-input m-input--air" x-select-2 x-no-submit>
                                @foreach( $services as $service )
                                    <option value="{{ $service -> id }}">{{ $service -> name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label for="type">Type</label>
                            <select name="type" class="form-control m-input m-input--air" x-select x-no-submit x-options-show-hide>
                                @foreach( \App\Models\ServiceInput::TYPES as $type => $name )
                                    <option value="{{ $type }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label for="step">Step</label>
                            <select name="step" class="form-control m-input m-input--air" x-select x-no-submit>
                                @foreach( \App\Models\ServiceInput::STEPS as $step )
                                    <option value="{{ $step }}">{{ $step }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group m-form__group hidden" style="margin: 0 auto; float: none;" x-coefficient>
                            <label>Coefficient</label>
                            <input name="coefficient" type="number" step="0.01" class="form-control m-input m-input--air" placeholder="Coefficient" value="<%= rc.coefficient %>">
                        </div>
                        <div class="col-sm-12 hidden" x-options>
                            <div class="m-portlet" style="margin: 15px;">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text">Options</h3>
                                        </div>
                                        <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-add-service-input-option style="position: absolute; right: 17px;">
                                            <i class="m-menu__link-icon la la-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="m-portlet__body" x-service-input-options></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <button type="submit" class="btn m-btn btn-primary pull-right"><% if( rc.id ) { %>Edit<% } else { %>Add<% } %></button>
                    </div>
                </div>
            </form>
        </div>
    </script>

    <script type="text/template" x-service-input-option>
        <div class="m-portlet" x-service-input-option-id="<% rc.id %>" style="position: relative;">
            <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-remove-service-input-option style="position: absolute; right: -12px; top: -12px;">
                <i class="m-menu__link-icon la la-close"></i>
            </button>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-sm-4">
                        <input name="options[<%= rc.k %>][name_en]" class="form-control m-input m-input--air" placeholder="Name ( EN )" value="<%= rc.name_en %>">
                    </div>
                    <div class="col-sm-4">
                        <input name="options[<%= rc.k %>][name_az]" class="form-control m-input m-input--air" placeholder="Name ( AZ )" value="<%= rc.name_az %>">
                    </div>
                    <div class="col-sm-4">
                        <input name="options[<%= rc.k %>][name_ru]" class="form-control m-input m-input--air" placeholder="Name ( RU )" value="<%= rc.name_ru %>">
                    </div>
                    <div class="col-sm-4" style="margin: 0 auto; float: none; margin-top: 5px;">
                        <input name="options[<%= rc.k %>][coefficient]" type="number" step="0.01" class="form-control m-input m-input--air" placeholder="Coefficient" value="<%= rc.coefficient %>">
                    </div>
                </div>
            </div>
        </div>
    </script>

@endsection
