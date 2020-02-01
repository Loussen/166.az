@php
    $title = 'FAQ';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('menu-header') @include('admin.menu-header') @endsection

@section('content')

    <form action="{{ route( 'admin.faq.list' ) }}" method="post" x-list-form="main">
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
                                            <th>Question ( Individual )</th>
                                            <th>Question ( Corporate )</th>
                                            <th>Service</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="2">
                                                <input type="text" class="form-control m-input m-input--air" name="question" placeholder="Question ( Individual / Corporate )">
                                            </th>
                                            <th>
                                                <select class="form-control m-input m-input--air" name="service" x-select>
                                                    <option>All</option>
                                                    @foreach( $services as $service )
                                                        <option value="{{ $service -> id }}">{{ $service -> name }}</option>
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
                                                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'faq.add' ) )
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
            <td><%= rc.individual_question %></td>
            <td><%= rc.corporate_question %></td>
            <td><%= rc.service %></td>
            <td>
                <span class="m-switch">
                    <label>
                        <input x-activate-url="{{ route('admin.faq.activate') }}" type="checkbox" <% if ( rc.is_active == 1 ){ %> checked="checked" <% } %> @if( ! \App\Http\Controllers\Admin\AdminController::CAN( 'faq.activate' ) ) disabled @endif>
                        <span></span>
                    </label>
                </span>
            </td>
            <td>
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'faq.edit' ) )
                    <button type="button" class="btn btn-sm m-btn btn-primary m-btn--icon m-btn--icon-only" x-edit-url="{{ route('admin.faq.get') }}">
                        <i class="m-menu__link-icon flaticon-edit"></i>
                    </button>
                @endif
                @if( \App\Http\Controllers\Admin\AdminController::CAN( 'faq.delete' ) )
                    <button type="button" class="btn btn-sm m-btn btn-dark m-btn--icon m-btn--icon-only" x-delete-url="{{ route('admin.faq.delete') }}">
                        <i class="m-menu__link-icon flaticon-delete-1"></i>
                    </button>
                @endif
            </td>
        </tr>
    </script>

    <script type="text/template" x-edit-modal>
        <div class="m-portlet">
            <form action="{{ route( 'admin.faq.edit' ) }}" class="m-form m-form--fit m-form--label-align-right" x-edit-form>
                <input type="hidden" name="id" value="<%= rc.id %>">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Question ( EN )</label>
                            <textarea rows="5" name="individual_question_en" class="form-control m-input m-input--air" placeholder="Individual Question ( EN )"><%= rc.individual_question_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Question ( AZ )</label>
                            <textarea rows="5" name="individual_question_az" class="form-control m-input m-input--air" placeholder="Individual Question ( AZ )"><%= rc.individual_question_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Question ( RU )</label>
                            <textarea rows="5" name="individual_question_ru" class="form-control m-input m-input--air" placeholder="Individual Question ( RU )"><%= rc.individual_question_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Question ( EN )</label>
                            <textarea rows="5" name="corporate_question_en" class="form-control m-input m-input--air" placeholder="Corporate Question ( EN )"><%= rc.corporate_question_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Question ( AZ )</label>
                            <textarea rows="5" name="corporate_question_az" class="form-control m-input m-input--air" placeholder="Corporate Question ( AZ )"><%= rc.corporate_question_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Question ( RU )</label>
                            <textarea rows="5" name="corporate_question_ru" class="form-control m-input m-input--air" placeholder="Corporate Question ( RU )"><%= rc.corporate_question_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Answer ( EN )</label>
                            <textarea rows="10" name="individual_answer_en" class="form-control m-input m-input--air" placeholder="Individual Answer ( EN )"><%= rc.individual_answer_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Answer ( AZ )</label>
                            <textarea rows="10" name="individual_answer_az" class="form-control m-input m-input--air" placeholder="Individual Answer ( AZ )"><%= rc.individual_answer_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Individual Answer ( RU )</label>
                            <textarea rows="10" name="individual_answer_ru" class="form-control m-input m-input--air" placeholder="Individual Answer ( RU )"><%= rc.individual_answer_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Answer ( EN )</label>
                            <textarea rows="10" name="corporate_answer_en" class="form-control m-input m-input--air" placeholder="Corporate Answer ( EN )"><%= rc.corporate_answer_en %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Answer ( AZ )</label>
                            <textarea rows="10" name="corporate_answer_az" class="form-control m-input m-input--air" placeholder="Corporate Answer ( AZ )"><%= rc.corporate_answer_az %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label>Corporate Answer ( RU )</label>
                            <textarea rows="10" name="corporate_answer_ru" class="form-control m-input m-input--air" placeholder="Corporate Answer ( RU )"><%= rc.corporate_answer_ru %></textarea>
                        </div>
                        <div class="col-sm-4 form-group m-form__group">
                            <label for="service">Service</label>
                            <select name="service" class="form-control m-input m-input--air" x-select x-no-submit>
                                <option value="">Select</option>
                                @foreach( $services as $service )
                                    <option value="{{ $service -> id }}">{{ $service -> name }}</option>
                                @endforeach
                            </select>
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
