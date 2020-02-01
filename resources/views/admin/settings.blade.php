@php
    $title = __('message.Settings');
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('content')

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <form action="{{ route( 'admin.changePassword' ) }}" method="post" x-edit-form x-redirect-url="{{ route( 'admin.dashboard' ) }}" class="m-form m-form--fit m-form--label-align-right">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>{{ __('message.OldPassword') }}</label>
                                    <input x-required type="password" class="form-control m-input m-input--air" name="oldPassword" placeholder="{{ __('message.OldPassword') }}">
                                </div>
                                <div class="col-sm-12">
                                    <label>{{ __('message.NewPassword') }}</label>
                                    <input x-required type="password" class="form-control m-input m-input--air" name="newPassword" placeholder="{{ __('message.NewPassword') }}">
                                </div>
                                <div class="col-sm-12">
                                    <label>{{ __('message.NewPasswordRepeat') }}</label>
                                    <input x-required type="password" class="form-control m-input m-input--air" name="newPasswordRepeat" placeholder="{{ __('message.NewPasswordRepeat') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn m-btn btn-primary pull-right">{{ __('message.ChangePassword') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <!--end::Portlet-->
        </div>
    </div>

@endsection
