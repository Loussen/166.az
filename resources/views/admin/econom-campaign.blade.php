@php
    $title = 'Econom campaigns';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.econom-campaign.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Title</th>
                                            <th>Service</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="title" placeholder="Title">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="service" x-select>
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
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'economCampaign.add' ) )
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
        function activities( activities )
        {
            $( '[x-checkbox-list="activities"]' ).html( '' );

            for( let i in activities )
            {
                $( '[x-checkbox-list="activities"]' ).append( _.template( $( 'script[x-campaign-activity]' ).html() )( activities[ i ] ) );
            }
        }

        function campaignActivities( data )
        {
            if( data.activities !== undefined && data.activities.length ) activities( data.activities );
        }

        $( document ).ready( function()
        {
            $( document ).on( 'change' , '[name="service"]' , function()
            {
                let t = $( this ) , service = t.val() , id = t.closest( '[x-tr-id]' ).attr( 'x-tr-id' );

                loading( 1 );

                $.post( '{{ route('admin.econom-campaign.includes') }}' , {
                    'campaign' : id ,
                    'service' : service ,
                    '_token' : csrf
                } ).done( function( res )
                {
                    if( res[ 'status' ] === 'success' )
                    {
                        loading();

                        activities( res.data );
                    } else
                    {
                        error( res.exception !== undefined ? ( res.exception.message + ' | Line: ' + res.exception.line + ' | File: ' + res.exception.file ) : ( res.warning !== undefined ? res.warning : '' ) );
                    }
                } ).fail( function()
                {
                    error( 'Network error!' );
                } );
            } );
        } );
    </script>

@endsection


@section('templates')

    <script type="text/template" x-tr>
        <tr x-tr-id="<%= rc.id %>">
            <td><%= rc.id %></td>
            <td><%= rc.title %></td>
            <td><%= rc.service %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.econom-campaign.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'economCampaign.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'economCampaign.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.econom-campaign.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'economCampaign.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.econom-campaign.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet" x-tr-id="<%= rc.id %>">
            <form action="{{ route( 'admin.econom-campaign.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Title ( EN )</label>
                            <textarea rows="2" name="title_en" class="form-control m-input m-input--air" placeholder="Title ( EN )"><%= rc.title_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Title ( AZ )</label>
                            <textarea rows="2" name="title_az" class="form-control m-input m-input--air" placeholder="Title ( AZ )"><%= rc.title_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Title ( RU )</label>
                            <textarea rows="2" name="title_ru" class="form-control m-input m-input--air" placeholder="Title ( RU )"><%= rc.title_ru %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Individual Text ( EN )</label>
                            <textarea rows="8" name="individual_text_en" class="form-control m-input m-input--air" placeholder="Individual Text ( EN )"><%= rc.individual_text_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Individual Text ( AZ )</label>
                            <textarea rows="8" name="individual_text_az" class="form-control m-input m-input--air" placeholder="Individual Text ( AZ )"><%= rc.individual_text_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Individual Text ( RU )</label>
                            <textarea rows="8" name="individual_text_ru" class="form-control m-input m-input--air" placeholder="Individual Text ( RU )"><%= rc.individual_text_ru %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Corporate Text ( EN )</label>
                            <textarea rows="8" name="corporate_text_en" class="form-control m-input m-input--air" placeholder="Corporate Text ( EN )"><%= rc.corporate_text_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Corporate Text ( AZ )</label>
                            <textarea rows="8" name="corporate_text_az" class="form-control m-input m-input--air" placeholder="Corporate Text ( AZ )"><%= rc.corporate_text_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Corporate Text ( RU )</label>
                            <textarea rows="8" name="corporate_text_ru" class="form-control m-input m-input--air" placeholder="Corporate Text ( RU )"><%= rc.corporate_text_ru %></textarea>
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label for="service">Service</label>
                            <select name="service" class="form-control m-input m-input--air" x-select x-no-submit>
                                <option value="">Select</option>
                                @foreach( $services as $service )
                                    <option value="{{ $service -> id }}">{{ $service -> name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Price</label>
                            <input name="price" class="form-control m-input m-input--air" placeholder="Price" value="<%= rc.price %>">
                        </div>
                        <div class="col-sm-12">
                            <div class="m-portlet" style="margin: 15px;">
                                <input type="hidden" name="activities">
                                <div class="m-portlet__body row" x-checkbox-list="activities"></div>
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

    <script type="text/template" x-campaign-activity>
        <div class="col-sm-4">
            <label class="m-checkbox m-checkbox--check-bold m-checkbox--state-primary">
                <input x-checkbox-id="<%= rc.id %>" type="checkbox" <% if ( rc.included == 1 ){ %> checked "<% } %>>
                <span></span><%= rc.name %>
            </label>
        </div>
    </script>

@endsection
