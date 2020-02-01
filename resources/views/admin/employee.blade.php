@php
    $title = 'Employees';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.employee.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control m-input m-input--air" name="name" placeholder="Name">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="active" x-select>
                                                    <option>All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </th>
                                            <th>
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'employee.add' ) )
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
            <td><%= rc.name %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.employee.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'employee.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'employee.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.employee.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'employee.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.employee.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.employee.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                            <label>Photo</label>
                            <img src="<%= media(rc.photo) %>" x-photo-img="photo" x-photo-default="<%= media(rc.photo) %>">
                            <input type="file" name="photo" accept="image/.*" x-photo-input="photo">
                        </div>
                        <div class="col-sm-8 row">
                            <div class="col-sm-6 form-group m-form__group">
                                <label>Facebook</label>
                                <input name="facebook" class="form-control m-input m-input--air" placeholder="Facebook" value="<%= rc.facebook %>">
                            </div>
                            <div class="col-sm-6 form-group m-form__group">
                                <label>Instagram</label>
                                <input name="instagram" class="form-control m-input m-input--air" placeholder="Instagram" value="<%= rc.instagram %>">
                            </div>
                            <div class="col-sm-6 form-group m-form__group">
                                <label>Twitter</label>
                                <input name="twitter" class="form-control m-input m-input--air" placeholder="Twitter" value="<%= rc.twitter %>">
                            </div>
                            <div class="col-sm-6 form-group m-form__group">
                                <label>Linkedin</label>
                                <input name="linkedin" class="form-control m-input m-input--air" placeholder="Linkedin" value="<%= rc.linkedin %>">
                            </div>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Name ( EN ) *</label>
                            <input name="name_en" class="form-control m-input m-input--air" placeholder="Name ( EN ) *" value="<%= rc.name_en %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Name ( AZ ) *</label>
                            <input name="name_az" class="form-control m-input m-input--air" placeholder="Name ( AZ ) *" value="<%= rc.name_az %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Name ( RU ) *</label>
                            <input name="name_ru" class="form-control m-input m-input--air" placeholder="Name ( RU ) *" value="<%= rc.name_ru %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Position ( EN ) *</label>
                            <input name="position_en" class="form-control m-input m-input--air" placeholder="Position ( EN ) *" value="<%= rc.position_en %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Position ( AZ ) *</label>
                            <input name="position_az" class="form-control m-input m-input--air" placeholder="Position ( AZ ) *" value="<%= rc.position_az %>">
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Position ( RU ) *</label>
                            <input name="position_ru" class="form-control m-input m-input--air" placeholder="Position ( RU ) *" value="<%= rc.position_ru %>">
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Mobile</label>
                            <input name="mobile" class="form-control m-input m-input--air" placeholder="Mobile" value="<%= rc.mobile %>">
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Email</label>
                            <input name="email" class="form-control m-input m-input--air" placeholder="Email" value="<%= rc.email %>">
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
