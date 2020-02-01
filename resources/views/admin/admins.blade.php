@php
    $title = 'Admins';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.admin.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Username</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="active" x-select>
                                                    <option>All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </th>
                                            <th>
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'admin.add' ) )
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
            <td><%= rc.username %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.admin.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> <% if ( rc.id == '{{ Auth::id() }}' ){ %>disabled<% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'admin.activate' ) ) disabled @endif>
                        <span></span>
                        </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'admin.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.admin.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'admin.delete' ) )
                    <% if ( rc.id != '{{ Auth::id() }}' ){ %>
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.admin.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                    <% } %>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.admin.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control m-input m-input--air" placeholder="Name" value="<%= rc.name %>">
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control m-input m-input--air" placeholder="Username" value="<%= rc.username %>">
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control m-input m-input--air" placeholder="Password">
                        </div>
                        <div class="col-sm-6 form-group m-form__group">
                            <label>Password repeat</label>
                            <input type="password" name="passwordRepeat" class="form-control m-input m-input--air" placeholder="Password repeat">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="m-portlet m-portlet--head-sm m-portlet--collapse m-portlet--primary m-portlet--head-solid-bg" m-portlet="true" id="m_portlet_tools_role">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Roles
                                        </h3>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools">
                                    <ul class="m-portlet__nav">
                                        <li class="m-portlet__nav-item">
                                            <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                                                <i class="la la-angle-down"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="m-portlet__body" m-hidden-height="344" style="display: none; overflow: hidden; padding-top: 0px; padding-bottom: 0px;">
                                <div class="m-scrollable m-scroller ps" data-scrollbar-shown="true" data-scrollable="true" data-height="300">
                                    <input type="hidden" name="roles" value="rc.roles">
                                    <div class="row" x-checkbox-list="roles">
                                        <div class="col-sm-12 row">
                                            @foreach( $roles as $key => $role )
                                                <div class="col-sm-6 col-md-4 mb-3" x-checkbox-group="{{ $key }}">
                                                    <label class="m-checkbox m-checkbox--check-bold m-checkbox--state-primary <% if ( rc.id == '{{ Auth::id() }}' ){ %>disabled"<% } %>">
                                                        <input x-checkbox-id="{{ $key }}" type="checkbox" <% if ( rc.id == '{{ Auth::id() }}' ){ %>disabled checked="checked"<% } %>>
                                                        <span></span>
                                                        {{ $role['name'] }}
                                                    </label>
                                                    @if( isset( $role['roles'] ) )
                                                        @foreach( $role['roles'] as $key_2 => $role_2 )
                                                            <div class="col-sm-12 mb-1" x-checkbox-group="{{ $key_2 }}">
                                                                <label class="m-checkbox m-checkbox--check-bold m-checkbox--state-primary <% if ( rc.id == '{{ Auth::id() }}' ){ %>disabled"<% } %>">
                                                                <input x-checkbox-id="{{ $key_2 }}" type="checkbox" <% if ( rc.id == '{{ Auth::id() }}' ){ %>disabled checked="checked"<% } %>>
                                                                <span></span>
                                                                {{ $role_2['name'] }}
                                                                </label>
                                                                @if( isset( $role_2['roles'] ) )
                                                                    @foreach( $role_2['roles'] as $key_3 => $role_3 )
                                                                        <div class="col-sm-12">
                                                                            <label class="m-checkbox m-checkbox--check-bold m-checkbox--state-primary <% if ( rc.id == '{{ Auth::id() }}' ){ %>disabled"<% } %>">
                                                                                <input x-checkbox-id="{{ $key_3 }}" type="checkbox" <% if ( rc.id == '{{ Auth::id() }}' ){ %>disabled checked="checked"<% } %>>
                                                                                <span></span>
                                                                                {{ $role_3['name'] }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
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

@endsection
