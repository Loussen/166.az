@php
    $titles = ['blog' => 'Blog' , 'media' => 'We at media' ];

     $title = $titles[ $type ];
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.post.list' ) }}" method="post" x-list-form="main">
        <input type="hidden" name="type" value="{{ $type }}">
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
                                            @if( $type == 'blog' )
                                                <th>Service</th>
                                            @endif
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="title" placeholder="Title">
                                            </th>
                                            @if( $type == 'blog' )
                                                <th>
                                                    <select class="form-control m-input m-input--air" name="service" x-select>
                                                        <option>All</option>
                                                        @foreach( $services as $service )
                                                            <option value="{{ $service -> id }}">{{ $service -> name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                            @endif
                                            <th>
                                                <select class="form-control m-input m-input--air" name="active" x-select>
                                                    <option>All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </th>
                                            <th>
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'post' . ucfirst( $type ) . '.add' ) )
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


@section('templates')

    <script type="text/template" x-tr>
        <tr x-tr-id="<%= rc.id %>">
            <td><%= rc.id %></td>
            <td><%= rc.title %></td>
            @if( $type == 'blog' )
                <td><%= rc.service %></td>
            @endif
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.post.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'post' . ucfirst( $type ) . '.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'post' . ucfirst( $type ) . '.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.post.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'post' . ucfirst( $type ) . '.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.post.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.post.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <% if( ! rc.id ) { %> <input type="hidden" name="type" value="{{ $type }}"> <% } %>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                            <label>Photo</label>
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
                            <label>Headline ( EN )</label>
                            <textarea rows="10" name="headline_en" class="form-control m-input m-input--air" placeholder="Headline ( EN )"><%= rc.headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Headline ( AZ )</label>
                            <textarea rows="10" name="headline_az" class="form-control m-input m-input--air" placeholder="Headline ( AZ )"><%= rc.headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Headline ( RU )</label>
                            <textarea rows="10" name="headline_ru" class="form-control m-input m-input--air" placeholder="Headline ( RU )"><%= rc.headline_ru %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Text ( EN )</label>
                            <textarea x-summernote name="text_en" class="form-control m-input m-input--air" placeholder="Text ( EN )"><%= rc.text_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Text ( AZ )</label>
                            <textarea x-summernote name="text_az" class="form-control m-input m-input--air" placeholder="Text ( AZ )"><%= rc.text_az %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>Text ( RU )</label>
                            <textarea x-summernote name="text_ru" class="form-control m-input m-input--air" placeholder="Text ( RU )"><%= rc.text_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Date</label>
                            <input x-datetime name="date" class="form-control m-input m-input--air" placeholder="Date" value="<%= rc.date %>" autocomplete="off">
                        </div>
                        @if( $type == 'blog' )
                            <div class="col-sm-4 form-group m-form__group">
                                <label for="service">Service</label>
                                <select name="service" class="form-control m-input m-input--air" x-select x-no-submit>
                                    <option value="">Select</option>
                                    @foreach( $services as $service )
                                        <option value="{{ $service -> id }}">{{ $service -> name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-sm-4 form-group m-form__group">
                                <div class="mb-2">New</div>
                                <span class="m-switch">
                                    <label>
                                        <input name="new" type="checkbox" <% if ( rc.is_new == 1 ){ %> checked="checked" <% } %>>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        @endif
                        <div class="col-sm-4 form-group m-form__group"></div>
                        <h4 class="ml-4 mt-5">SEO</h4>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>SEO Keywords ( EN )</label>
                            <input x-tagsinput name="seo_keywords_en" class="form-control m-input m-input--air" placeholder="SEO Keywords ( EN )" value="<%= rc.seo_keywords_en %>">
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>SEO Keywords ( AZ )</label>
                            <input x-tagsinput name="seo_keywords_az" class="form-control m-input m-input--air" placeholder="SEO Keywords ( AZ )" value="<%= rc.seo_keywords_az %>">
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>SEO Keywords ( RU )</label>
                            <input x-tagsinput name="seo_keywords_ru" class="form-control m-input m-input--air" placeholder="SEO Keywords ( RU )" value="<%= rc.seo_keywords_ru %>">
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>SEO Description ( EN )</label>
                            <textarea rows="5" name="seo_description_en" class="form-control m-input m-input--air" placeholder="SEO Description ( EN )"><%= rc.seo_description_en %></textarea>
                        </div>
                        <div class="col-sm-12 form-group m-form__group">
                            <label>SEO Description ( AZ )</label>
                            <textarea rows="5" name="seo_description_az" class="form-control m-input m-input--air" placeholder="SEO Description ( AZ )"><%= rc.seo_description_az %></textarea>
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
