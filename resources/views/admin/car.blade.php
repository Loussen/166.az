@php
    $title = 'Cars';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.car.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Type</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="question" placeholder="Title">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="type" x-select>
                                                    <option>All</option>
                                                    @foreach( $types as $type )
                                                        <option value="{{ $type -> id }}">{{ $type -> name }}</option>
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
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'car.add' ) )
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
            <td><%= rc.type %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.car.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'car.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'car.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.car.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'car.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.car.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.car.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-3 form-group m-form__group upload-photo" x-photo>
                            <label>Photo</label>
                            <img src="<%= media(rc.photo) %>" x-photo-img="photo" x-photo-default="<%= media(rc.photo) %>">
                            <input type="file" name="photo" accept="image/.*" x-photo-input="photo">
                        </div>
                        <div class="col-sm-3 form-group m-form__group upload-photo" x-photo>
                            <label>Background photo</label>
                            <img src="<%= media(rc.background) %>" x-photo-img="background" x-photo-default="<%= media(rc.background) %>">
                            <input type="file" name="background" accept="image/.*" x-photo-input="background">
                        </div>
                        <div class="col-sm-3 form-group m-form__group upload-photo" x-photo>
                            <label>Open Graph image</label>
                            <img src="<%= media(rc.og_image) %>" x-photo-img="og_image" x-photo-default="<%= media(rc.og_image) %>">
                            <input type="file" name="og_image" accept="image/.*" x-photo-input="og_image">
                        </div>
                        <div class="col-sm-3 form-group m-form__group upload-photo" x-photo>
                            <label>Palets photo</label>
                            <img src="<%= media(rc.palet_photo) %>" x-photo-img="palet_photo" x-photo-default="<%= media(rc.palet_photo) %>">
                            <input type="file" name="palet_photo" accept="image/.*" x-photo-input="palet_photo">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Title ( EN )</label>
                            <input name="title_en" class="form-control m-input m-input--air" placeholder="Title ( EN )" value="<%= rc.title_en %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Title ( AZ )</label>
                            <input name="title_az" class="form-control m-input m-input--air" placeholder="Title ( AZ )" value="<%= rc.title_az %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Title ( RU )</label>
                            <input name="title_ru" class="form-control m-input m-input--air" placeholder="Title ( RU )" value="<%= rc.title_ru %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Headline ( EN )</label>
                            <textarea rows="5" name="headline_en" class="form-control m-input m-input--air" placeholder="Headline ( EN )"><%= rc.headline_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Headline ( AZ )</label>
                            <textarea rows="5" name="headline_az" class="form-control m-input m-input--air" placeholder="Headline ( AZ )"><%= rc.headline_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Headline ( RU )</label>
                            <textarea rows="5" name="headline_ru" class="form-control m-input m-input--air" placeholder="Headline ( RU )"><%= rc.headline_ru %></textarea>
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
                        <div class="col-sm-2 form-group m-form__group">
                            <label for="type">Type</label>
                            <select name="type" class="form-control m-input m-input--air" x-select x-no-submit>
                                <option value="">Select</option>
                                @foreach( $types as $type )
                                    <option value="{{ $type -> id }}">{{ $type -> name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2 form-group m-form__group">
                            <label>Length</label>
                            <input name="length" class="form-control m-input m-input--air" placeholder="Length" value="<%= rc.length %>">
                        </div>
                        <div class="col-sm-2 form-group m-form__group">
                            <label>Height</label>
                            <input name="height" class="form-control m-input m-input--air" placeholder="Height" value="<%= rc.height %>">
                        </div>
                        <div class="col-sm-2 form-group m-form__group">
                            <label>Width</label>
                            <input name="width" class="form-control m-input m-input--air" placeholder="Width" value="<%= rc.width %>">
                        </div>
                        <div class="col-sm-2 form-group m-form__group">
                            <label>Palet</label>
                            <input name="palet" class="form-control m-input m-input--air" placeholder="Palet" value="<%= rc.palet %>">
                        </div>
                        <div class="col-sm-2 form-group m-form__group">
                            <label>Weight</label>
                            <input name="weight" class="form-control m-input m-input--air" placeholder="Weight" value="<%= rc.weight %>">
                        </div>
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
