@php
    $title = 'Callback';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.callback.list' ) }}" method="post" x-list-form="main">
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
                                        <th>City</th>
                                        <th>Service</th>
                                        <th>Status</th>
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
                                            <input type="text" class="form-control m-input m-input--air" name="city" placeholder="City">
                                        </th>
                                        <th>
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
                                        </th>
                                        <th>
                                            <select class="form-control m-input m-input--air" name="is_active" x-select>
                                                <option>All</option>
                                                <option value="1">Aktiv</option>
                                                <option value="0">Deaktiv</option>
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
            <td><%= rc.name %></td>
            <td><%= rc.phone %></td>
            <td><%= rc.city %></td>
            <td><%= rc.service %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.callback.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'callback.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'callback.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.callback.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>
@endsection
