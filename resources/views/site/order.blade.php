@php

    $selectedService = $services[0] -> id;

    if( app('request') -> input('service') ) $selectedService = app('request') -> input('service');

@endphp

@extends('site.index')

@section('content')

    <main>
        <div class="container mb-5 page-order">
            <div class="order">
                <div id="order-success-modal" class="modal fade modal-service">
                    <div class="modal-dialog modal-service__dialog">
                        <div class="modal-content modal-service__content">
                            <div class="modal-header modal-service__header">
                                <h6>{{ __('message.We_will_call_you') }}</h6>
                            </div>
                            <div class="modal-close" data-dismiss="modal">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('service-inputs') }}" method="post" x-order-form x-edit-form x-target="afterOrder">
                    <input type="hidden" name="parent" value="{{ $selectedService }}">
                    <input type="hidden" name="name" value="{{ app('request') -> input('name') }}">
                    <input type="hidden" name="phone" value="{{ app('request') -> input('phone') }}">
                    <input type="hidden" name="hour">
                    <input type="hidden" name="date">
                    <input type="hidden" name="service" value="1">
                    <input type="hidden" name="address_1">
                    <input type="hidden" name="address_2">
                    <input type="hidden" name="parameters[elevator_1]">
                    <input type="hidden" name="parameters[floor_1]">
                    <input type="hidden" name="parameters[elevator_2]">
                    <input type="hidden" name="parameters[floor_2]">
                    <input type="hidden" name="parameters[area]">
                    <input type="hidden" name="parameters[floor]">
                    <input type="hidden" name="parameters[chair]">
                    <input type="hidden" name="parameters[semolition_installation]">
                    <input type="hidden" name="parameters[item]">
                    <input type="hidden" name="parameters[reason]">
                    <input type="hidden" name="parameters[car_type]">
                    <input type="hidden" name="parameters[master_service]">
                    <input type="hidden" name="parameters[room]">
                    <input type="hidden" name="parameters[workforce]">
                    <input type="hidden" name="parameters[box]">
                    <input type="hidden" name="parameters[child]">
                    <input type="hidden" name="parameters[bed]">
                    <input type="hidden" name="parameters[servant]">
                    <input type="hidden" name="parameters[kamot]">
                    <input type="hidden" name="parameters[table]">
                    <input type="hidden" name="parameters[chair]">
                    <input type="hidden" name="parameters[departure_hour]">
                    <input type="hidden" name="parameters[departure_date]">
                    <div class="order-top">
                        <div class="order-top__left step">
                            <h1>{{ __('message.Order_Service') }}</h1>
                            <p>
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('message.Step_1') }}</span>
                            </p>
                            <div class="form-row">
                                <label>{{ __('message.Service') }}</label>
                                <select id="step1" x-name="parent">
                                    @foreach( $services as $service )
                                        <option value="{{ $service -> id }}" class="service_{{ $service -> id }}" @if( $selectedService == $service -> id ) selected @endif>{{ $service -> name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                            <div class="form-row">
                                <label for="name">{{ __('message.Your_name') }}</label>
                                <input type="text" x-name="name" placeholder="{{ __('message.Your_name') }}" value="{{ app('request') -> input('name') }}">
                            </div>
                            <div class="form-row">
                                <label for="phone">{{ __('message.Phone') }}</label>
                                <input type="text" x-name="phone" placeholder="{{ __('message.Phone') }}" value="{{ app('request') -> input('phone') }}">
                            </div>
                        </div>
                        <div class="order-top__center step">
                            @foreach( $services as $service )
                                <div class="order-top__center--item service_{{ $service -> id }} {{ $selectedService == $service -> id ? 'd-block' : 'd-none' }}">
                                    <h1></h1>
                                    <p>
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ __('message.Step_2') }}</span>
                                    </p>
                                    @if( count( $service -> children ) )
                                        <div class="form-row">
                                            <label for="service"></label>
                                            <select x-name="service">
                                                @foreach( $service -> children as $childService )
                                                    <option value="{{ $childService -> id }}">{{ $childService -> name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-row">
                                        <label for="address_1">{{ $service -> id == 4 ? __('message.Address_Service_4') : ( __('message.Address') . ( $service -> id == 1 || $service -> id == 25 || $service -> id == 22 ? ' 1' : '' ) ) }}</label>
                                        <input type="text" x-name="address_1">
                                    </div>
                                    @if( $service -> id == 1 )
                                        <div class="form-row">
                                            <label for="address_2">{{ __('message.Address') }} 2</label>
                                            <input type="text" x-name="address_2">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-row--left form-element--long">
                                                <label>{{ __('message.Address') }} 1 {{ __('message.Elevator') }}</label>
                                                <select x-name="parameters[elevator_1]">
                                                    <option value="1">{{ __('message.Exists') }}</option>
                                                    <option value="0">{{ __('message.No') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label>{{ __('message.Floor') }} 1</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[floor_1]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-row--left form-element--long">
                                                <label>name</label>
                                                <select name="id">
                                                    <option value="id">name</option>
                                                    <option value="0">{{ __('message.No') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label>{{ __('message.Floor') }} 2</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[floor_2]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif( $service -> id == 4 )
                                        <div class="form-row">
                                            <div class="form-row--left form-element--short">
                                                <label>{{ __('message.Area') }}</label>
                                                <input type="text" x-name="parameters[area]">
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label>{{ __('message.Floor') }}</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[floor]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label>{{ __('message.Chairs') }}</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[chair]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif( $service -> id == 3 )
                                        <div class="form-row">
                                            <label>{{ __('message.Area') }}</label>
                                            <input type="text" x-name="parameters[area]">
                                        </div>
                                    @elseif( $service -> id == 25 )
                                        <div class="form-row">
                                            <label for="address_2">{{ __('message.Address') }} 2</label>
                                            <input type="text" x-name="address_2">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-row--left form-element--long">
                                                <label>{{ __('message.Semolition_installation') }}</label>
                                                <select x-name="parameters[semolition_installation]">
                                                    <option value="1">{{ __('message.Yes') }}</option>
                                                    <option value="0">{{ __('message.No') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label>{{ __('message.Items') }}</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[item]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif( $service -> id == 23 )
                                        <div class="form-row">
                                            <label>{{ __('message.Area') }}</label>
                                            <input type="text" x-name="parameters[area]">
                                        </div>
                                    @elseif( $service -> id == 22 )
                                        <div class="form-row">
                                            <label for="address_2">{{ __('message.Address') }} 2</label>
                                            <input type="text" x-name="address_2">
                                        </div>
                                        <div class="form-row">
                                            <label>{{ __('message.Reason_of_towing') }}</label>
                                            <input type="text" x-name="parameters[reason]">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="order-top__right step">
                            @foreach( $services as $service )
                                <div class="order-top__right--item service_{{ $service -> id }} {{ $selectedService == $service -> id ? 'd-block' : 'd-none' }}">
                                    <p>
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ __('message.Step_3') }}</span>
                                    </p>
                                    <div class="form-row form-row--date">
                                        <div class="form-row--left form-element--short">
                                            <label for="hour">{{ $service -> id == 23 ?  __('message.Date_of_entry') : __('message.Hour') }}</label>
                                            <div class="select-row">
                                                <div class="select-icon"><i class="far fa-clock"></i></div>
                                                <select x-name="hour">
                                                    @for( $m = 0; $m < 24; $m++ )
                                                        @php
                                                            $hour = ( $m < 10 ? '0' :'' ) . $m ;
                                                        @endphp
                                                        <option value="{{ $hour }}:00">{{ $hour }}:00</option>
                                                        <option value="{{ $hour }}:30">{{ $hour }}:30</option>
                                                    @endfor
                                                </select>
                                                <input class="datetime-container" placeholder="13:30" id="datetimepicker1" data-target="#datetimepicker1" data-toggle="datetimepicker" autocomplete="off" />
                                            </div>
                                        </div>
                                        <div class="form-row--right form-element--long">
                                            <label for="date"> @if( $service -> id != 23 ) {{ __('message.Date') }} @endif</label>
                                            <div class="datetime select-row" data-bind="daterangepicker: dateRange">
                                                <div class="select-icon"><i class="far fa-calendar-check"></i></div>
                                                <input type="hidden" id="datetime-text">
                                                <div class="datetime-container">
                                                    <span class="datetime-text"></span>
                                                    <i class="fa fa-sort-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if( $service -> id == 1 )
                                        <div class="form-row">
                                            <label>{{ __('message.Car_type') }}</label>
                                            <select x-name="parameters[car_type]">
                                                @foreach( $carTypes as $carType )
                                                    <option value="{{ $carType -> id }}">{{ $carType -> name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <label>{{ __('message.Master_service') }}</label>
                                            <select x-name="parameters[master_service]">
                                                <option value="1">{{ __('message.Yes') }}</option>
                                                <option value="0">{{ __('message.No') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-row--left form-element--short">
                                                <label>{{ __('message.Rooms') }}</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[room]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                            <div class="form-row--center form-element--short">
                                                <label>{{ __('message.Workforce') }}</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[workforce]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label>{{ __('message.Boxes') }}</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[box]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif( $service -> id == 25 )
                                        <div class="form-row">
                                            <div class="form-row--left form-element--short">
                                                <label for="">Uşaq otağı</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[child]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                            <div class="form-row--center form-element--short">
                                                <label for="">Yataq otağı</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[bed]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label for="">Servant</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[servant]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-row--left form-element--short">
                                                <label for="">Kamot</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[kamot]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                            <div class="form-row--center form-element--short">
                                                <label for="">Stol</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[table]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                            <div class="form-row--right form-element--short">
                                                <label for="">Stul</label>
                                                <div class="regulator">
                                                    <div class="regulator-minus"><span>-</span></div>
                                                    <input class="regulator-output" type="text" value="0" disabled x-name="parameters[chair]">
                                                    <div class="regulator-plus"><span>+</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif( $service -> id == 23 )
                                        <div class="form-row form-row--date">
                                            <div class="form-row--left form-element--short">
                                                <label>{{ __('message.Date_of_departure') }}</label>
                                                <div class="select-row">
                                                    <div class="select-icon"><i class="far fa-clock"></i></div>
                                                    <input class="datetime-container" placeholder="13:30" id="datetimepicker1" data-target="#datetimepicker1" data-toggle="datetimepicker" autocomplete="off" />
                                                    <select x-name="parameters[departure_hour]">
                                                        @for( $m = 0; $m < 24; $m++ )
                                                            @php
                                                                $hour = ( $m < 10 ? '0' :'' ) . $m ;
                                                            @endphp
                                                            <option value="{{ $hour }}:00">{{ $hour }}:00</option>
                                                            <option value="{{ $hour }}:30">{{ $hour }}:30</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row--right form-element--long">
                                                <label> </label>
                                                <div class="datetime select-row" data-bind="daterangepicker: dateRange">
                                                    <div class="select-icon"><i class="far fa-calendar-check"></i></div>
                                                    <input type="hidden" id="datetime-text">
                                                    <div class="datetime-container">
                                                        <span class="datetime-text"></span>
                                                        <i class="fa fa-sort-down"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="order-bottom">
                        <div class="order-bottom__left">
                            <div class="form-row">
                            </div>
                        </div>
                        <div class="order-bottom__right">
                            <div class="order-all">
                                <div class="order-all__center hidden">
                                    <p><span x-order-selected-service>Yükdaşıma xidməti</span>:
                                        <strong><span x-order-selected-service-price>500</span> AZN</strong>
                                    </p>
                                </div>
                                <div class="order-all__right">
                                    <h2 class="hidden">{{ __('message.Final_price') }}:
                                        <strong><span x-order-final-price>630</span> AZN</strong>
                                    </h2>
                                    <div class="form-submit form-animated">
                                        <button type="submit" disabled x-order-submit style="width: 208px;">
                                            <span>{{ __('message.Continue') }}</span>
                                            <div class="form-animated__over">
                                                <i class="fa fa-long-arrow-right"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection


@section('extra_js')

    <script>
        function calculate()
        {
            let parent = $( '[x-order-form]' ).find( '[x-name="parent"]' ).val() ,
                name_ = $( '[x-order-form]' ).find( '[x-name="name"]' ) ,
                name = name_.val().trim() ,
                phone_ = $( '[x-order-form]' ).find( '[x-name="phone"]' ) ,
                phone = phone_.val().trim() ,
                address_1_ = $( '[x-order-form]' ).find( '[x-name="address_1"]' ) ,
                address_1 = address_1_.val().trim() ,
                address_2_ = $( '[x-order-form]' ).find( '.service_' + parent ).find( '[x-name="address_2"]' ) ,
                address_2 = address_2_.length ? address_2_.val().trim() : '' ,
                ok = true;

            name_.css( 'border' , '1px solid #f7f7f7' );
            phone_.css( 'border' , '1px solid #f7f7f7' );
            address_1_.css( 'border' , '1px solid #f7f7f7' );
            address_2_.css( 'border' , '1px solid #f7f7f7' );

            $( '[name="service"]' ).val( $( '[x-order-form]' ).find( '.service_' + parent ).find( '[x-name="service"]' ).length ? $( '[x-order-form]' ).find( '.service_' + parent ).find( '[x-name="service"]' ).val() : '' );

            $( '[x-order-submit]' ).attr( 'disabled' , 'disabled' );

            if( !name.length )
            {
                ok = false;
                name_.css( 'border' , '1px solid red' );
            }

            if( !phone.length )
            {
                ok = false;
                phone_.css( 'border' , '1px solid red' );
            }

            if( !address_1.length )
            {
                ok = false;
                address_1_.css( 'border' , '1px solid red' );
            }

            if( address_2_.length && !address_2.length )
            {
                ok = false;
                address_2_.css( 'border' , '1px solid red' );
            }

            if( ok )
            {
                $( '[x-order-submit]' ).removeAttr( 'disabled' );
            }
        }


        function afterOrder()
        {
            $( "#order-success-modal" ).modal( 'show' );

            $( '[x-order-form]' ).trigger( 'reset' );
        }


        $( document ).ready( function()
        {
            $( document ).on( 'change' , '[x-name]' , function()
            {
                $( '[x-order-form] [name="' + $( this ).attr( 'x-name' ) + '"]' ).val( $( this ).val().trim() );

                calculate();
            } );

            calculate();
        } );
    </script>

@endsection
