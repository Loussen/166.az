@php
    $title = 'Orders';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.order.list' ) }}" method="post" x-list-form="main">
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
                                        <th>Phone</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>
                                            <input type="text" class="form-control m-input m-input--air" name="name" placeholder="Name">
                                        </th>
                                        <th>
                                            <input type="text" class="form-control m-input m-input--air" name="phone" placeholder="Phone">
                                        </th>
                                        <th>
                                            <input type="text" class="form-control m-input m-input--air" name="total" placeholder="Total">
                                        </th>
                                        <th>
                                            <select class="form-control m-input m-input--air" name="status" x-select>
                                                <option>All</option>
                                                <option value="1">Paid</option>
                                                <option value="0">Waiting</option>
                                            </select>
                                        </th>
                                        <th>
                                            <select class="form-control m-input m-input--air" name="is_order" x-select>
                                                <option>All</option>
                                                <option value="0">Calculation</option>
                                                <option value="1">Order</option>
                                            </select>
                                        </th>
                                        <th>
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






    </script>

@endsection


@section('templates')

    @parent()

    <script type="text/template" x-tr>
        <tr x-tr-id="<%= rc.id %>">
            <td><%= rc.id %></td>
            <td><%= rc.name %></td>
            <td><%= rc.phone %></td>
            <td><%= rc.total %> AZN</td>
            <td>
                <% if ( rc.status == 1 ){ %>
                <span class="badge badge-success">Paid</span>
                <% } else { %>
                <span class="badge badge-danger">Waiting</span>
                <% }  %>
            </td>
            <td>
                <% if ( rc.is_order == 1 ){ %>
                <span class="badge badge-success">Order</span>
                <% } else { %>
                <span class="badge badge-warning">Calculation</span>
                <% }  %>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'order.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.order.get') }}">
                        <i class="m-menu__link-icon flaticon-eye"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'order.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.order.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.order.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-12 row">
                            <div class="col-sm-2 form-group m-form__group" x-step step-0 step-1>
                                <strong>Name: </strong>
                                <%= rc.name %>
                            </div>
                            <div class="col-sm-2 form-group m-form__group" x-step step-0 step-1>
                                <strong>Phone: </strong>
                                <%= rc.phone %>
                            </div>
                            <div class="col-sm-2 form-group m-form__group" x-step step-0 step-1>
                                <strong>Total: </strong>
                                <%= rc.total %>
                            </div>
                            <div class="col-sm-2 form-group m-form__group" x-step step-0 step-1>
                                <strong>Type: </strong>
                                <% if ( rc.is_order == 1 ){ %>
                                <span class="badge badge-success">Order</span>
                                <% } else { %>
                                <span class="badge badge-danger">Calculation</span>
                                <% }  %>
                            </div>
                            <div class="col-sm-2 form-group m-form__group" x-step step-0 step-1>
                                <strong>Payment status: </strong>
                                <% if ( rc.status == 1 ){ %>
                                <span class="badge badge-success">Paid</span>
                                <% } else { %>
                                <span class="badge badge-danger">Waiting</span>
                                <% }  %>
                            </div>
                            <div class="col-sm-2 form-group m-form__group" x-step step-0 step-1>
                                <strong>Service count: </strong>
                                5
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </script>

@endsection
