@php
    $title = 'Services';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.service.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Parent</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="name" placeholder="Name">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="parent" x-select-2-url="{{ route('admin.service.search') }}" x-data-column="all" x-data-value="1">
                                                    <option>All</option>
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
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'service.add' ) )
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
        function campaignActivities( data )
        {
            images( data );

            step();

            setTimeout( function()
            {
                $( '[name="parent"][x-no-submit]' ).select2( 'trigger' , 'select' , {
                    data : {
                        id : data.parent_id ,
                        text : data.parent_name
                    }
                } );
            } , 55 );
        }

        function addDependency()
        {
            step();
        }

        function step()
        {
            loading( 1 );

            $.post( '{{ route('admin.service.step') }}' , {
                'id' : $( '[name="parent"][x-no-submit]' ).val() ,
                '_token' : csrf
            } ).done( function( res )
            {
                if( res[ 'status' ] === 'success' )
                {
                    $( '[x-step]' ).addClass( 'hidden' );

                    $( '[x-step][step-' + res.data + ']' ).removeClass( 'hidden' );

                    setTimeout( function()
                    {
                        loading();
                    } , 55 );
                } else
                {
                    setTimeout( function()
                    {
                        loading();
                    } , 55 );
                }
            } ).fail( function()
            {
                setTimeout( function()
                {
                    loading();
                } , 55 );
            } );
        }

        $( document ).on( 'change' , '[name="parent"][x-no-submit]' , function()
        {
            step();
        } );
    </script>

@endsection


@section('templates')

    @parent()

    <script type="text/template" x-tr>
        <tr x-tr-id="<%= rc.id %>">
            <td><%= rc.id %></td>
            <td><%= rc.name %></td>
            <td><%= rc.parent_name %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.service.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'service.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'service.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.service.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'service.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.service.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.service.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-12 row">
                            <div class="col-sm-6 form-group m-form__group">
                                <label>Parent</label>
                                <select name="parent" class="form-control m-input m-input--air" x-select-2-url="{{ route('admin.service.search') }}" x-data-column="service" x-data-value="1" x-data-column2="except" x-data-value2="<%= rc.id %>" x-no-submit>
                                    <option>Parent</option>
                                </select>
                            </div>
                            <div class="col-sm-6 form-group m-form__group" x-step step-0 step-1>
                                <label>Base price</label>
                                <input type="number" min="0" max="99999" autocomplete="off" name="price" class="form-control m-input m-input--air" placeholder="Base price" value="<%= rc.price %>">
                            </div>
                        </div>
                        <div class="col-sm-12 row">
                            <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                                <label>Photo</label>
                                <img src="<%= media(rc.photo) %>" x-photo-img="photo" x-photo-default="<%= media(rc.photo) %>">
                                <input type="file" name="photo" accept="image/.*" x-photo-input="photo">
                            </div>
                            <div class="col-sm-4 form-group m-form__group upload-photo" x-photo x-step step-0 step-1>
                                <label>Background photo</label>
                                <img src="<%= media(rc.background) %>" x-photo-img="background" x-photo-default="<%= media(rc.background) %>">
                                <input type="file" name="background" accept="image/.*" x-photo-input="background">
                            </div>
                            <div class="col-sm-4 form-group m-form__group upload-photo" x-photo x-step step-0 step-1>
                                <label>Open Graph image</label>
                                <img src="<%= media(rc.og_image) %>" x-photo-img="og_image" x-photo-default="<%= media(rc.og_image) %>">
                                <input type="file" name="og_image" accept="image/.*" x-photo-input="og_image">
                            </div>
                        </div>
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
                            <label>Individual Headline ( EN )</label>
                            <textarea rows="6" name="individual_headline_en" class="form-control m-input m-input--air" placeholder="Individual Headline ( EN )"><%= rc.individual_headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Headline ( AZ )</label>
                            <textarea rows="6" name="individual_headline_az" class="form-control m-input m-input--air" placeholder="Individual Headline ( AZ )"><%= rc.individual_headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Headline ( RU )</label>
                            <textarea rows="6" name="individual_headline_ru" class="form-control m-input m-input--air" placeholder="Individual Headline ( RU )"><%= rc.individual_headline_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Headline ( EN )</label>
                            <textarea rows="6" name="corporate_headline_en" class="form-control m-input m-input--air" placeholder="Corporate Headline ( EN )"><%= rc.corporate_headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Headline ( AZ )</label>
                            <textarea rows="6" name="corporate_headline_az" class="form-control m-input m-input--air" placeholder="Corporate Headline ( AZ )"><%= rc.corporate_headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Headline ( RU )</label>
                            <textarea rows="6" name="corporate_headline_ru" class="form-control m-input m-input--air" placeholder="Corporate Headline ( RU )"><%= rc.corporate_headline_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group" x-step step-1>
                            <label>Include Headline ( EN )</label>
                            <textarea rows="6" name="include_headline_en" class="form-control m-input m-input--air" placeholder="Include Headline ( EN )"><%= rc.include_headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group" x-step step-1>
                            <label>Include Headline ( AZ )</label>
                            <textarea rows="6" name="include_headline_az" class="form-control m-input m-input--air" placeholder="Include Headline ( AZ )"><%= rc.include_headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group" x-step step-1>
                            <label>Include Headline ( RU )</label>
                            <textarea rows="6" name="include_headline_ru" class="form-control m-input m-input--air" placeholder="Include Headline ( RU )"><%= rc.include_headline_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group" x-step step-0>
                            <label>Extra info Headline ( EN )</label>
                            <textarea rows="6" name="extra_info_headline_en" class="form-control m-input m-input--air" placeholder="Extra info Headline ( EN )"><%= rc.extra_info_headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group" x-step step-0>
                            <label>Extra info Headline ( AZ )</label>
                            <textarea rows="6" name="extra_info_headline_az" class="form-control m-input m-input--air" placeholder="Extra info Headline ( AZ )"><%= rc.extra_info_headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group" x-step step-0>
                            <label>Extra info Headline ( RU )</label>
                            <textarea rows="6" name="extra_info_headline_ru" class="form-control m-input m-input--air" placeholder="Extra info Headline ( RU )"><%= rc.extra_info_headline_ru %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0 step-1>
                            <label>Individual Description ( EN )</label>
                            <textarea x-summernote name="individual_description_en" class="form-control m-input m-input--air" placeholder="Individual Description ( EN )"><%= rc.individual_description_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0 step-1>
                            <label>Individual Description ( AZ )</label>
                            <textarea x-summernote name="individual_description_az" class="form-control m-input m-input--air" placeholder="Individual Description ( AZ )"><%= rc.individual_description_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0 step-1>
                            <label>Individual Description ( RU )</label>
                            <textarea x-summernote name="individual_description_ru" class="form-control m-input m-input--air" placeholder="Individual Description ( RU )"><%= rc.individual_description_ru %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0 step-1>
                            <label>Corporate Description ( EN )</label>
                            <textarea x-summernote name="corporate_description_en" class="form-control m-input m-input--air" placeholder="Corporate Description ( EN )"><%= rc.corporate_description_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0 step-1>
                            <label>Corporate Description ( AZ )</label>
                            <textarea x-summernote name="corporate_description_az" class="form-control m-input m-input--air" placeholder="Corporate Description ( AZ )"><%= rc.corporate_description_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0 step-1>
                            <label>Corporate Description ( RU )</label>
                            <textarea x-summernote name="corporate_description_ru" class="form-control m-input m-input--air" placeholder="Corporate Description ( RU )"><%= rc.corporate_description_ru %></textarea>
                        </div>
                        <h4 class="ml-4 mt-5" x-step step-0>SEO</h4>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0>
                            <label>SEO Keywords ( EN )</label>
                            <input x-tagsinput name="seo_keywords_en" class="form-control m-input m-input--air" placeholder="SEO Keywords ( EN )" value="<%= rc.seo_keywords_en %>">
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0>
                            <label>SEO Keywords ( AZ )</label>
                            <input x-tagsinput name="seo_keywords_az" class="form-control m-input m-input--air" placeholder="SEO Keywords ( AZ )" value="<%= rc.seo_keywords_az %>">
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0>
                            <label>SEO Keywords ( RU )</label>
                            <input x-tagsinput name="seo_keywords_ru" class="form-control m-input m-input--air" placeholder="SEO Keywords ( RU )" value="<%= rc.seo_keywords_ru %>">
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0>
                            <label>SEO Description ( EN )</label>
                            <textarea rows="5" name="seo_description_en" class="form-control m-input m-input--air" placeholder="SEO Description ( EN )"><%= rc.seo_description_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0>
                            <label>SEO Description ( AZ )</label>
                            <textarea rows="5" name="seo_description_az" class="form-control m-input m-input--air" placeholder="SEO Description ( AZ )"><%= rc.seo_description_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group" x-step step-0>
                            <label>SEO Description ( RU )</label>
                            <textarea rows="5" name="seo_description_ru" class="form-control m-input m-input--air" placeholder="SEO Description ( RU )"><%= rc.seo_description_ru %></textarea>
                        </div>
                        <div class="col-sm-12 row" x-images x-step step-0>
                            <input type="hidden" name="deleted_files">
                        </div>
                        <div class="col-sm-6 form-group m-form__group" x-step step-0>
                            <label style="display: flex;">Images for gallery</label>
                            <input type="file" accept="image/*" multiple name="images[]">
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

@endsection
