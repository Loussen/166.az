@php
    $title = 'Sliders';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.slider.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Link</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="link" placeholder="Link">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="active" x-select>
                                                    <option>All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </th>
                                            <th>
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'slider.add' ) )
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
            <td><%= rc.link %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.slider.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'slider.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'slider.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.slider.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'slider.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.slider.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.slider.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-6 form-group m-form__group upload-photo" x-photo>
                            <label>Photo</label>
                            <img src="<%= media(rc.photo) %>" x-photo-img="photo" x-photo-default="<%= media(rc.photo) %>">
                            <input type="file" name="photo" accept="image/.*" x-photo-input="photo">
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Link *</label>
                            <input name="link" class="form-control m-input m-input--air" placeholder="Link *" value="<%= rc.link %>">
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
