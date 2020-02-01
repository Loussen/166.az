@php
    $title = 'Vacancies';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.vacancy.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="title" placeholder="Title">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="active" x-select>
                                                    <option>All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </th>
                                            <th>
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'vacancy.add' ) )
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

        function addVacancyDetail( detail = {} )
        {
            detail.k = k++;

            $( '[x-vacancy-details]' ).append( _.template( $( 'script[x-vacancy-detail]' ).html() )( detail ) );
        }

        function vacancyDetails( data )
        {
            if( data.details !== undefined && data.details.length )
            {
                for( let i in data.details )
                {
                    addVacancyDetail( data.details[ i ] );
                }
            }
        }


        function addVacancyRequirement( requirement = {} )
        {
            requirement.k = k++;

            $( '[x-vacancy-requirements]' ).append( _.template( $( 'script[x-vacancy-requirement]' ).html() )( requirement ) );
        }

        function vacancyRequirements( data )
        {
            if( data.requirements !== undefined && data.requirements.length )
            {
                for( let i in data.requirements )
                {
                    addVacancyRequirement( data.requirements[ i ] );
                }
            }
        }


        function campaignActivities( data )
        {
            vacancyDetails( data );

            vacancyRequirements( data );
        }


        $( document ).ready( function()
        {
            $( document ).on( 'click' , '[x-add-vacancy-detail]' , function()
            {
                addVacancyDetail();
            } );

            $( document ).on( 'click' , '[x-remove-vacancy-detail]' , function()
            {
                $( this ).closest( '[x-vacancy-detail-id]' ).fadeOut( function()
                {
                    $( this ).remove();
                } );
            } );


            $( document ).on( 'click' , '[x-add-vacancy-requirement]' , function()
            {
                addVacancyRequirement();
            } );

            $( document ).on( 'click' , '[x-remove-vacancy-requirement]' , function()
            {
                $( this ).closest( '[x-vacancy-requirement-id]' ).fadeOut( function()
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
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.vacancy.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'vacancy.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'vacancy.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.vacancy.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'vacancy.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.vacancy.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.vacancy.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                            <label>Blog photo</label>
                            <img src="<%= media(rc.photo) %>" x-photo-img="photo" x-photo-default="<%= media(rc.photo) %>">
                            <input type="file" name="photo" accept="image/.*" x-photo-input="photo">
                        </div>
                        <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                            <label>Background photo</label>
                            <img src="<%= media(rc.background) %>" x-photo-img="background" x-photo-default="<%= media(rc.background) %>">
                            <input type="file" name="background" accept="image/.*" x-photo-input="background">
                        </div>
                        <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                            <label>Open Graph image</label>
                            <img src="<%= media(rc.og_image) %>" x-photo-img="og_image" x-photo-default="<%= media(rc.og_image) %>">
                            <input type="file" name="og_image" accept="image/.*" x-photo-input="og_image">
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
                            <label>Text ( EN )</label>
                            <textarea rows="10" name="text_en" class="form-control m-input m-input--air" placeholder="Text ( EN )"><%= rc.text_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Text ( AZ )</label>
                            <textarea rows="10" name="text_az" class="form-control m-input m-input--air" placeholder="Text ( AZ )"><%= rc.text_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Text ( RU )</label>
                            <textarea rows="10" name="text_ru" class="form-control m-input m-input--air" placeholder="Text ( RU )"><%= rc.text_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Note ( EN )</label>
                            <textarea rows="10" name="note_en" x-summernote class="form-control m-input m-input--air" placeholder="Note ( EN )"><%= rc.note_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Note ( AZ )</label>
                            <textarea rows="10" name="note_az" x-summernote class="form-control m-input m-input--air" placeholder="Note ( AZ )"><%= rc.note_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Note ( RU )</label>
                            <textarea rows="10" name="note_ru" x-summernote class="form-control m-input m-input--air" placeholder="Note ( RU )"><%= rc.note_ru %></textarea>
                        </div>
                        <div class="col-sm-12">
                            <div class="m-portlet" style="margin: 15px;">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text">Details</h3>
                                        </div>
                                        <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-add-vacancy-detail style="position: absolute; right: 17px;">
                                            <i class="m-menu__link-icon la la-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="m-portlet__body" x-vacancy-details></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="m-portlet" style="margin: 15px;">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text">Requirements</h3>
                                        </div>
                                        <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-add-vacancy-requirement style="position: absolute; right: 17px;">
                                            <i class="m-menu__link-icon la la-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="m-portlet__body" x-vacancy-requirements></div>
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

    <script type="text/template" x-vacancy-detail>
        <div class="m-portlet" x-vacancy-detail-id="<% rc.id %>" style="position: relative;">
            <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-remove-vacancy-detail style="position: absolute; right: -12px; top: -12px;">
                <i class="m-menu__link-icon la la-close"></i>
            </button>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-sm-4">
                        <textarea rows="2" name="details[<%= rc.k %>][name_en]" class="form-control m-input m-input--air" placeholder="EN"><%= rc.name_en %></textarea>
                    </div>
                    <div class="col-sm-4">
                        <textarea rows="2" name="details[<%= rc.k %>][name_az]" class="form-control m-input m-input--air" placeholder="AZ"><%= rc.name_az %></textarea>
                    </div>
                    <div class="col-sm-4">
                        <textarea rows="2" name="details[<%= rc.k %>][name_ru]" class="form-control m-input m-input--air" placeholder="RU"><%= rc.name_ru %></textarea>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" x-vacancy-requirement>
        <div class="m-portlet" x-vacancy-requirement-id="<% rc.id %>" style="position: relative;">
            <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-remove-vacancy-requirement style="position: absolute; right: -12px; top: -12px;">
                <i class="m-menu__link-icon la la-close"></i>
            </button>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-sm-4">
                        <textarea rows="2" name="requirements[<%= rc.k %>][name_en]" class="form-control m-input m-input--air" placeholder="EN"><%= rc.name_en %></textarea>
                    </div>
                    <div class="col-sm-4">
                        <textarea rows="2" name="requirements[<%= rc.k %>][name_az]" class="form-control m-input m-input--air" placeholder="AZ"><%= rc.name_az %></textarea>
                    </div>
                    <div class="col-sm-4">
                        <textarea rows="2" name="requirements[<%= rc.k %>][name_ru]" class="form-control m-input m-input--air" placeholder="RU"><%= rc.name_ru %></textarea>
                    </div>
                </div>
            </div>
        </div>
    </script>

@endsection
