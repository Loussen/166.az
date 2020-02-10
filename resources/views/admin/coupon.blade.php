@php
    $title = 'Coupons';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.coupon.list' ) }}" method="post" x-list-form="main">
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
                                        <th>Phone</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th>Activation</th>
                                        <th>Actions</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>
                                            <input type="text" class="form-control m-input m-input--air" name="phone" placeholder="Phone">
                                        </th>
                                        <th>
                                            <input type="text" class="form-control m-input m-input--air" name="code" placeholder="Code">
                                        </th>
                                        <!--th>
                                            <select name="service_id" id="" class="form-control m-input m-input--air">
                                                <option value=""><?php echo e(__('message.Service')); ?></option>
                                                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if( count( $service -> children ) ): ?>
                                                <optgroup label="<?php echo e($service -> name); ?>">
                                                    <?php $__currentLoopData = $service -> children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($childService -> id); ?>"><?php echo e($childService -> name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                                <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </th-->
                                        <th>
                                            <select class="form-control m-input m-input--air" name="is_active" x-select>
                                                <option>All</option>
                                                <option value="0">Deactive</option>
                                                <option value="1">Active</option>
                                                <option value="2">Used</option>
                                            </select>
                                        </th>
                                        <th>Activation
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

        let k = 0;

        function addCampaignActivity( activity = {} )
        {
            activity.k = k++;

            $( '[x-order-services]' ).append( _.template( $( 'script[x-order-service]' ).html() )( activity ) );
        }

        function campaignActivities( data )
        {
            if( data.services !== undefined && data.services.length )
            {
                for( let i in data.services )
                {
                    console.log('service');
                    addCampaignActivity( data.services[ i ] );
                }
            }
        }





    </script>

@endsection


@section('templates')

    @parent()

    <script type="text/template" x-tr>
        <tr x-tr-id="<%= rc.id %>">
            <td><%= rc.id %></td>
            <td><%= rc.phone %></td>
            <td><%= rc.code %></td>
            <td>
                <% if ( rc.is_active == 1 ){ %>
                <span class="badge badge-success">Active</span>
                <% } else if( rc.is_active == 2 ) { %>
                <span class="badge badge-danger">Used</span>
                <% } else { %>
                <span class="badge badge-warning">Deactive</span>
                <% }  %>
            </td>
            <td>
                <% if(rc.is_active != 2) { %>
                <span class="m-switch">
                    <label>
                        <input x-activate-url-refresh="{{ route('admin.coupon.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'coupon.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
                <% } %>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'coupon.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.coupon.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

@endsection
