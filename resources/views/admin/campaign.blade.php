@php
    $title = 'Campaigns';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.campaign.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Start date</th>
                                            <th>End date</th>
                                            <th>Service</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="title" placeholder="Title">
                                            </th>
                                            <th></th>
                                            <th></th>
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
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'campaign.add' ) )
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

            $( '[x-campaign-activities]' ).append( _.template( $( 'script[x-campaign-activity]' ).html() )( activity ) );
        }

        function campaignActivities( data )
        {
            if( data.activities !== undefined && data.activities.length )
            {
                for( let i in data.activities )
                {
                    addCampaignActivity( data.activities[ i ] );
                }
            }
        }

        $( document ).ready( function()
        {
            $( document ).on( 'click' , '[x-add-campaign-activity]' , function()
            {
                addCampaignActivity();
            } );

            $( document ).on( 'click' , '[x-remove-campaign-activity]' , function()
            {
                $( this ).closest( '[x-campaign-activity-id]' ).fadeOut( function()
                {
                    $( this ).remove();
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
            <td><%= rc.start_date %></td>
            <td><%= rc.end_date %></td>
            <td><%= rc.service %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.campaign.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'campaign.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'campaign.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.campaign.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'campaign.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.campaign.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.campaign.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                            <label>Photo</label>
                            <img src="<%= media(rc.photo) %>" x-photo-img="photo" x-photo-default="<%= media(rc.photo) %>">
                            <input type="file" name="photo" accept="image/.*" x-photo-input="photo">
                        </div>
                        <div class="col-sm-8 row">
                            <div class="col-sm-12 form-group m-form__group">
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
                            <div class="col-sm-6 form-group m-form__group">
                                <label for="input">Input</label>
                                <select name="input" class="form-control m-input m-input--air" x-select x-no-submit>
                                    <option value="email">Email</option>
                                    <option value="phone" <% if( rc.input == 'phone' ) { %> selected <% } %>>Phone</option>
                                </select>
                            </div>
                        </div>
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
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Headline ( EN )</label>
                            <textarea rows="10" name="individual_headline_en" class="form-control m-input m-input--air" placeholder="Individual Headline ( EN )"><%= rc.individual_headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Headline ( AZ )</label>
                            <textarea rows="10" name="individual_headline_az" class="form-control m-input m-input--air" placeholder="Individual Headline ( AZ )"><%= rc.individual_headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Headline ( RU )</label>
                            <textarea rows="10" name="individual_headline_ru" class="form-control m-input m-input--air" placeholder="Individual Headline ( RU )"><%= rc.individual_headline_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Headline ( EN )</label>
                            <textarea rows="10" name="corporate_headline_en" class="form-control m-input m-input--air" placeholder="Corporate Headline ( EN )"><%= rc.corporate_headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Headline ( AZ )</label>
                            <textarea rows="10" name="corporate_headline_az" class="form-control m-input m-input--air" placeholder="Corporate Headline ( AZ )"><%= rc.corporate_headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Headline ( RU )</label>
                            <textarea rows="10" name="corporate_headline_ru" class="form-control m-input m-input--air" placeholder="Corporate Headline ( RU )"><%= rc.corporate_headline_ru %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Individual Text ( EN )</label>
                            <textarea x-summernote name="individual_text_en" class="form-control m-input m-input--air" placeholder="Individual Text ( EN )"><%= rc.individual_text_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Individual Text ( AZ )</label>
                            <textarea x-summernote name="individual_text_az" class="form-control m-input m-input--air" placeholder="Individual Text ( AZ )"><%= rc.individual_text_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Individual Text ( RU )</label>
                            <textarea x-summernote name="individual_text_ru" class="form-control m-input m-input--air" placeholder="Individual Text ( RU )"><%= rc.individual_text_ru %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Corporate Text ( EN )</label>
                            <textarea x-summernote name="corporate_text_en" class="form-control m-input m-input--air" placeholder="Corporate Text ( EN )"><%= rc.corporate_text_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Corporate Text ( AZ )</label>
                            <textarea x-summernote name="corporate_text_az" class="form-control m-input m-input--air" placeholder="Corporate Text ( AZ )"><%= rc.corporate_text_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Corporate Text ( RU )</label>
                            <textarea x-summernote name="corporate_text_ru" class="form-control m-input m-input--air" placeholder="Corporate Text ( RU )"><%= rc.corporate_text_ru %></textarea>
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Start date</label>
                            <input x-date name="start_date" class="form-control m-input m-input--air" placeholder="Start date" value="<%= rc.start_date %>">
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>End date</label>
                            <input x-date name="end_date" class="form-control m-input m-input--air" placeholder="End date" value="<%= rc.end_date %>">
                        </div>
                        <div class="col-sm-12">
                            <div class="m-portlet" style="margin: 15px;">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text">Activities</h3>
                                        </div>
                                        <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-add-campaign-activity style="position: absolute; right: 17px;">
                                            <i class="m-menu__link-icon la la-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="m-portlet__body" x-campaign-activities></div>
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
        <div class="m-portlet" x-campaign-activity-id="<% rc.id %>" style="position: relative;">
            <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-remove-campaign-activity style="position: absolute; right: -12px; top: -12px;">
                <i class="m-menu__link-icon la la-close"></i>
            </button>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-sm-4">
                        <textarea rows="2" name="activities[<%= rc.k %>][name_en]" class="form-control m-input m-input--air" placeholder="Name ( EN )"><%= rc.name_en %></textarea>
                    </div>
                    <div class="col-sm-4">
                        <textarea rows="2" name="activities[<%= rc.k %>][name_az]" class="form-control m-input m-input--air" placeholder="Name ( AZ )"><%= rc.name_az %></textarea>
                    </div>
                    <div class="col-sm-4">
                        <textarea rows="2" name="activities[<%= rc.k %>][name_ru]" class="form-control m-input m-input--air" placeholder="Name ( RU )"><%= rc.name_ru %></textarea>
                    </div>
                </div>
            </div>
        </div>
    </script>

@endsection
