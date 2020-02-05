<style>

    .pac-container {
        z-index: 99999;
    }

    .newOrder .modal-dialog {
        max-width: unset;
        width: max-content;
    }

    .form-row--number {
        display: inline-block;
        margin-top: 1.25rem;
    }

    .form-row--number input[type="text"] {
        height: 2.5rem;
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background-color: #f7f7f7;
        border: unset;
        padding-left: .5rem;
        padding-right: 1rem;
        color: #333537;
        font-size: .75rem;
        font-weight: 400;
    }

    .form-row--number label {
        display: block;
        margin-bottom: .5625rem;
        color: #98999a;
        font-size: .6875rem;
        font-weight: 500;
        line-height: .875rem;
        white-space: nowrap;
    }

    .daterangepicker {
        z-index: 1051;
    }

    .calendar .calendar-table .table-row .table-col.start-date .table-value-wrapper:hover {
        background: #ffc425;
    }

    .bootstrap-datetimepicker-widget table td span {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        margin: 2px 1.5px;
        cursor: pointer;
        border-radius: 0.25rem;
        color: #ffc425;
        font-size: .625rem;
    }

    .bootstrap-datetimepicker-widget .timepicker-hour, .bootstrap-datetimepicker-widget .timepicker-minute, .bootstrap-datetimepicker-widget .timepicker-second {
        color: black;
    }

    .googleMap {
        width: 100%;
        height: 370px;
        display: none;
    }

</style>

<div id="newOrder" class="modal fade newOrder">
    <div class="modal-dialog modal-service__dialog">
        <div class="modal-content modal-service__content">
            <div class="modal-header modal-service__header">
                <h6>{{ __('message.Order_Service') }}</h6>
            </div>
            <div class="modal-body modal-service__body">
                <form action="{{ route('order') }}" method="post" x-order-form x-target="afterOrder" class="form order-form">
                    <div id="order-forms" class="order-forms">
                        <div class="order-top" data-id="1">
                            <div class="order-top__left step">
                                <p>
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ __('message.Step_1') }}</span>
                                </p>
                                <div class="form-row">
                                    <label>{{ __('message.Service') }}</label>
                                    <select id="step1" name="parent">
                                        @foreach( $services as $service )
                                            <option value="{{ $service -> id }}" class="service_{{ $service -> id }}">{{ $service -> name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr>
                                <div class="form-row">
                                    <label for="name">{{ __('message.Your_name') }}</label>
                                    <input type="text" name="name" placeholder="{{ __('message.Your_name') }}">
                                </div>
                                <div class="form-row">
                                    <label for="phone">{{ __('message.Phone') }}</label>
                                    <input type="text" name="phone" placeholder="{{ __('message.Phone') }}">
                                </div>
                            </div>
                            <div class="order-top__center step" id="step_2">
                                <p>
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ __('message.Step_2') }}</span>
                                </p>
                                <div class="order-top__children"></div>
                                <div class="order-top__inputs"></div>
                                <div class="order-top__children-inputs"></div>
                            </div>
                            <div class="order-top__right step" id="step_3">
                                <p>
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ __('message.Step_3') }}</span>
                                </p>
                                <div class="order-top__children"></div>
                                <div class="order-top__inputs"></div>
                                <div class="order-top__children-inputs"></div>
                            </div>
                        </div>
                    </div>
                    <div class="order-middle">
                        <div id="order-ready" class="order-ready d-flex"></div>
                        <button type="button" id="order-new" class="order-new" data-toggle="modal" data-target="#newService">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <span>Xidmət əlavə et</span>
                        </button>
                        <div class="form-submit form-animated order-advertisement d-none">
                            <button type="submit">
                                <span>TƏKLİF GÖNDƏR</span>
                                <div class="form-animated__over">
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="order-bottom">
                        <div class="order-bottom__left">
                            <hr>
                            <div class="form-row">
                                <div class="form-row--left form-element--middle">
                                    <label for="">Endirim Kodu</label>
                                    <input type="text" value="XXXXXX">
                                </div>
                                <div class="form-row--right form-element--middle">
                                    <label for="">&nbsp;</label>
                                    <button class="button-all" value="ƏLAVƏ et">ƏLAVƏ et</button>
                                </div>
                            </div>
                        </div>
                        <div class="order-bottom__right">
                            <div class="order-all" style="background-color: #252728;">
                                <div class="order-all__left">
                                    <p><strong>10%</strong> ENDİRİM</p>
                                </div>
                                <div class="order-all__center">
                                    <p><span x-order-selected-service>Yükdaşıma xidməti</span>:
                                        <strong><span x-order-selected-service-price>500</span> AZN</strong>
                                    </p>
                                </div>
                                <div class="order-all__right">
                                    <h2 class="hidden">{{ __('message.Final_price') }}:
                                        <strong><span x-order-final-price>630</span> AZN</strong>
                                    </h2>
                                    <div class="form-submit form-animated">
                                        <button type="submit" x-order-submit style="width: 208px;">
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
            <div class="modal-close" data-dismiss="modal">
                <i class="fa fa-times"></i>
            </div>
        </div>
    </div>
</div>
<div id="mapModal" class="modal fade">
    <div class="modal-dialog modal-service__dialog">
        <div class="modal-content modal-service__content">
            <div class="modal-header modal-service__header">
                <h6>Ünvanı seçin</h6>
            </div>
            <div class="modal-body modal-service__body">
                <div id="mapBody" class="map-body"></div>
            </div>
            <div class="modal-close" data-dismiss="modal">
                <i class="fa fa-times"></i>
            </div>
        </div>
    </div>
</div>

<script>


    $('body').on('click', '[data-target="#newOrder"]', function () {
        let homeServiceValue = $('.select-service').find('option:selected').attr('value');
        let homeServiceName = $('.order input[name="name"]').val();
        let homeServicePhone = $('.order input[name="phone"]').val();
        let changeService = homeServiceValue;
        $('#step1 option').each(function () {
            var optVal = $(this).val();
            if (optVal == changeService) {
                $(this).prop('selected', true);
                let $this = $(this).parents('.order-top');
                post(changeService, $this);
            }
        });

        $('.order-top__left input[name="name"]').val(homeServiceName);
        $('.order-top__left input[name="phone"]').val(homeServicePhone);
    });

    $('body').on('change', '#step1', function () {
        /*let orderTopLength = $('.order-top').length;*/
        let orderTopId = $(this).parents('.order-top').attr('data-id');
        if (orderTopId >= 1) {

            let orderStepService = $(this).find('option:selected').text();
            $('.order-ready').each(function () {
                let orderTopId2 = $(this).attr('data-id');
                if (orderTopId2 == orderTopId) {
                    $(this).find('.order-ready__item span').text(orderStepService);
                    orderTopId = null;
                }
            });
        }

        let changeService = $(this).find('option:selected').attr('value');
        let $this = $(this).parents('.order-top');


        post(changeService, $this);

    });

    $('body').on('click', '#order-new', function () {
        let arr = [];
        $('.order-top').each(function () {
            arr.push($(this));
        });
        let orderLenght = arr.length + 1;
        let newOrder =
            `

            <div data-id=${orderLenght} class="order-top">
                            <div class="order-top__left step">

                                <h1></h1>
                                <p>
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ __('message.Step_1') }}</span>
                                </p>
                                <div class="form-row">
                                    <label>{{ __('message.Service') }}</label>
                                    <select id="step1" name="parent">
                                        @foreach( $services as $service )
                <option value="{{ $service -> id }}" class="service_{{ $service -> id }}">{{ $service -> name }}</option>
                                        @endforeach
                </select>
            </div>
            <hr>

                </div>
                <div class="order-top__center step" id="step_2">
                    <h1></h1>
                    <p>
                        <i class="fas fa-check-circle"></i>
                        <span>{{ __('message.Step_2') }}</span>
                                </p>
                                <div class="order-top__inputs">

                                </div>

            </div>
            <div class="order-top__right step" id="step_3">
                <h1></h1>
                <p>
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('message.Step_3') }}</span>
                                </p>
                                <div class="order-top__inputs">

                                </div>

            </div>
        </div>

`;

        $('#order-forms').append(newOrder);

        let orderServiceText;

        $('.order-top').each(function () {
            var dataIdService = $(this).attr('data-id');
            if (dataIdService == orderLenght) {
                orderServiceText = $(this).find('.order-top__left select[name="parent"] option:selected').text();
                return orderServiceText;
            }
        });

        let newServiceDeleteButton =
            `
                <div data-id=${orderLenght} class="order-ready d-flex">
                        <button type="button" class="order-ready__item" data-toggle="modal" data-target="#newOrder">
                            <span>${orderServiceText}</span>
                        </button>
                        <button type="button" class="order-trash">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                </div>
            `

        $('#order-ready').append(newServiceDeleteButton);

    });

    $('body').on('click', '.order-trash', function () {
        let dataId = $(this).parent().attr('data-id');
        $('.order-top').each(function () {
            let dataId2 = $(this).attr('data-id');
            if (dataId2 == dataId) {
                $(this).remove();
            }
        });

        $(this).parent().remove();
    });

   $('body').on('change', '.order-children', function () {
        let changeService = $(this).find('option:selected').attr('value');
        let $this = $(this).parents('.order-top');
        let m_id = $($this).attr("data-id");
        let index = parseInt(m_id) - 1;
        $.ajax({
            type: 'POST',
            url: '{{ route('service-inputs') }}',
            data: {
                service: changeService,
                '_token': csrf
            },
            success: function (res) {

                console.log(res.data);
                $($this).find('.order-top__children-inputs').html('');
                let childrenArr = res.data;

                for(let ch = 0; ch < childrenArr.length; ch++) {
                    let appendArea = $($this).find('#step_' + childrenArr[ch].step + ' .order-top__children-inputs');
                    if(childrenArr[ch].type === 'input') {
                        let htmlInput =
                            `
                            <div class="form-row">
                                        <label>${childrenArr[ch].name}</label>
                                        <input type="text" name="service[${changeService}][${childrenArr[ch].id}]" id="${childrenArr[ch].id}">
                            </div>
                            `;
                        appendArea.append(htmlInput);
                    } else if (childrenArr[ch].type === 'number') {
                        let htmlNumber =
                            `
                              <div class="form-row--number form-element--short">
                                     <label>${childrenArr[ch].name}</label>
                                        <div class="regulator">
                                             <div class="regulator-minus"><span>-</span></div>
                                                <input class="regulator-output" name="service[${changeService}][${childrenArr[ch].id}]" type="text" value="0" readonly>
                                             <div class="regulator-plus"><span>+</span></div>
                                        </div>
                              </div>
                            `;
                        appendArea.append(htmlNumber);
                    } else if (childrenArr[ch].type === 'date') {
                        let htmlDate =
                            `
                         <div class="form-row form-row--date">
                                        <div class="form-row--left form-element--short">
                                            <label for="hour">${childrenArr[ch].name}</label>
                                            <div class="select-row">
                                                <div class="select-icon"><i class="far fa-clock" aria-hidden="true"></i></div>

                                                <input class="datetime-container" placeholder="13:30" name="service[${changeService}][${childrenArr[ch].id}]" id="datetimepicker1" data-target="#datetimepicker1" data-toggle="datetimepicker" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-row--right form-element--long">
                                            <label for="date" style="visibility: hidden;">${childrenArr[ch].name}</label>
                                            <div class="datetime select-row" data-bind="daterangepicker: dateRange">
                                                <div class="select-icon"><i class="far fa-calendar-check" aria-hidden="true"></i></div>
                                                <input type="hidden" name="service[${changeService}][${childrenArr[ch].id}]" id="datetime-text" value="16 January 2020">
                                                <div class="datetime-container">
                                                    <span class="datetime-text">16 January 2020</span>
                                                    <i class="fa fa-sort-down" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                          `;
                        appendArea.append(htmlDate);
                        $('#datetimepicker1').datetimepicker({
                            format: 'LT',
                            locale: 'az'
                        });
                        $(".datetime-text").text(moment().format('D MMMM YYYY'));
                        $("#datetime-text").val(moment().format('D MMMM YYYY'));
                        $(".datetime").daterangepicker({
                            orientation: 'left',
                            expanded: true,
                            single: true,
                            locale: {applyButtonTitle: 'Apply', cancelButtonTitle: 'Cancel', endLabel: 'End', startLabel: 'Start'},
                            callback: function (endDate) {
                                $(this).find(".datetime-text").text(endDate.format('D MMMM YYYY'));
                                //input values on change start
                                $(this).find("#datetime-text").val(endDate.format('D MMMM YYYY'));
                                //input values on change end
                            }
                        });
                    } else if (childrenArr[ch].type === 'select') {
                        let htmlSelect =
                            `
                             <div class="form-row">
                                  <label>${childrenArr[ch].name}</label>
                                  <select name="service[${changeService}][${childrenArr[ch].id}]">
                                        ${
                                childrenArr[ch].options.map(item => (
                                    `<option value="${item.id}">${item.name}</option>`
                                )).join('')

                            }

                                  </select>
                            </div>
                        `;
                        appendArea.append(htmlSelect);
                    } else if (childrenArr[ch].type === 'address') {
                        let htmlAddress =
                            `
                            <div class="form-row">
                                        <label>${childrenArr[ch].name}</label>
                                        <input type="text" class="order-address" name="service[${changeService}][${childrenArr[ch].id}]" id="${m_id}address${childrenArr[ch].id}" name="${childrenArr[ch].name}">
                            </div>
                            `;
                        appendArea.append(htmlAddress);
                        if (!addressList[index]) {
                            addressList.push({
                                input: [m_id + "address" + childrenArr[ch].id],
                                complete: [null],
                                geometry: [{
                                    text: '',
                                    lat: null,
                                    lng: null
                                }],
                                directionsService: null,
                                directionsDisplay: null,
                                marker: null,
                                core: null
                            });
                        } else {
                            addressList[index].input.push(m_id + "address" + childrenArr[ch].id);
                            addressList[index].complete.push(null);
                            addressList[index].geometry.push({
                                text: '',
                                lat: null,
                                lng: null
                            });
                        }
                        addressFound = true;
                    }
                }
                if (addressFound) {
                    $($this).append('<div class="w-100"><div id="map' + m_id + '" class="googleMap"></div></div>');
                }
                setTimeout(function () {
                    console.log('addreslist');
                    console.log(addressList);
                    addressList[index].input.map(function (m, i) {
                        let input = document.getElementById(m);
                        google.maps.event.addDomListener(input, 'keydown', function (event) {
                            if (event.keyCode === 13) {
                                event.preventDefault();
                            }
                        });
                        addressList[index].complete[i] = new google.maps.places.Autocomplete((input), {types: ['geocode']});
                        addressList[index].complete[i].addListener('place_changed', function () {
                            let place = addressList[index].complete[i].getPlace();
                            addressList[index].geometry[i].text = place.formatted_address;
                            if (place.geometry.location) {
                                addressList[index].geometry[i].lat = place.geometry.location.lat();
                                addressList[index].geometry[i].lng = place.geometry.location.lng();
                            }
                            let found = 0;
                            addressList[index].geometry.map(geo => {
                                if (geo.text !== '') found++;
                            });
                            if (found > 0) {
                                $($this).find("#map" + m_id).show();
                            }
                            if (found === 1) {
                                if (addressList[index].core !== null) {
                                    setMarker(addressList[index], true);
                                } else {
                                    initMap("map" + m_id, addressList[index])
                                }
                            } else if (found > 1) {
                                setMarker(addressList[index], false);
                                setDirection(addressList[index]);
                            } else {
                                $($this).find("#map" + m_id).hide();
                            }
                        });
                    });
                }, 100);
            }
        });

    });

    var addressList = [];

    function post(changeService, $this) {
        let m_id = $($this).attr("data-id");
        let index = parseInt(m_id) - 1;
        $.ajax({
            type: 'POST',
            url: '{{ route('service-inputs') }}',
            data: {
                service: changeService,
                '_token': csrf
            },
            success: function (res) {
                console.log(res);
                $($this).find('.order-top__inputs').html('');
                $($this).find('.order-top__children').html('');
                $($this).find('.order-top__children-inputs').html('');
                addressList = [];
                let arr = res.data;
                let childrenArr = res.children;
                console.log(arr);
                console.log(childrenArr);
                let arrLength = childrenArr.length;
                if(arrLength > 0) {

                        let childrenSelect =
                            `
                             <div class="form-row">
                                  <label>Xidmətin növü</label>
                                  <select class="order-children" name="service[${changeService}][children]}]">
                                        ${
                                childrenArr.map(item => (
                                    `<option value="${item.id}">${item.name}</option>`
                                )).join('')

                            }

                                  </select>
                            </div>
                        `;

                        $($this).find('#step_2 .order-top__children').append(childrenSelect);
                    }




                let addressFound = false;
                for (let input = 0; input < arr.length; input++) {
                    let appendArea = $($this).find('#step_' + arr[input].step + ' .order-top__inputs');
                    if (arr[input].type === 'select') {
                        let htmlSelect =
                            `
                             <div class="form-row">
                                  <label>${arr[input].name}</label>
                                  <select name="service[${changeService}][${arr[input].id}]">
                                        ${
                                arr[input].options.map(item => (
                                    `<option value="${item.id}">${item.name}</option>`
                                )).join('')

                                }

                                  </select>
                            </div>
                        `;
                        appendArea.append(htmlSelect);
                    } else if (arr[input].type === 'input') {
                        let htmlInput =
                            `
                            <div class="form-row">
                                        <label>${arr[input].name}</label>
                                        <input type="text" name="service[${changeService}][${arr[input].id}]" id="${arr[input].id}">
                            </div>
                            `;
                        appendArea.append(htmlInput);

                    } else if (arr[input].type === 'number') {
                        let htmlNumber =
                            `

                              <div class="form-row--number form-element--short">
                                     <label>${arr[input].name}</label>
                                        <div class="regulator">
                                             <div class="regulator-minus"><span>-</span></div>
                                                <input class="regulator-output" name="service[${changeService}][${arr[input].id}]" type="text" value="0" readonly>
                                             <div class="regulator-plus"><span>+</span></div>
                                        </div>
                              </div>
                            `;
                        appendArea.append(htmlNumber);
                    } else if (arr[input].type === 'date') {
                        let htmlDate =
                            `
                         <div class="form-row form-row--date">
                                        <div class="form-row--left form-element--short">
                                            <label for="hour">${arr[input].name}</label>
                                            <div class="select-row">
                                                <div class="select-icon"><i class="far fa-clock" aria-hidden="true"></i></div>

                                                <input class="datetime-container" placeholder="13:30" name="service[${changeService}][${arr[input].id}]" id="datetimepicker1" data-target="#datetimepicker1" data-toggle="datetimepicker" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-row--right form-element--long">
                                            <label for="date" style="visibility: hidden;">${arr[input].name}</label>
                                            <div class="datetime select-row" data-bind="daterangepicker: dateRange">
                                                <div class="select-icon"><i class="far fa-calendar-check" aria-hidden="true"></i></div>
                                                <input type="hidden" name="service[${changeService}][${arr[input].id}]" id="datetime-text" value="16 January 2020">
                                                <div class="datetime-container">
                                                    <span class="datetime-text">16 January 2020</span>
                                                    <i class="fa fa-sort-down" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                          `;
                        appendArea.append(htmlDate);
                        $('#datetimepicker1').datetimepicker({
                            format: 'LT',
                            locale: 'az'
                        });
                        $(".datetime-text").text(moment().format('D MMMM YYYY'));
                        $("#datetime-text").val(moment().format('D MMMM YYYY'));
                        $(".datetime").daterangepicker({
                            orientation: 'left',
                            expanded: true,
                            single: true,
                            locale: {applyButtonTitle: 'Apply', cancelButtonTitle: 'Cancel', endLabel: 'End', startLabel: 'Start'},
                            callback: function (endDate) {
                                $(this).find(".datetime-text").text(endDate.format('D MMMM YYYY'));
                                //input values on change start
                                $(this).find("#datetime-text").val(endDate.format('D MMMM YYYY'));
                                //input values on change end
                            }
                        });
                    } else if (arr[input].type === 'address') {
                        let htmlAddress =
                            `
                            <div class="form-row">
                                        <label>${arr[input].name}</label>
                                        <input type="text" class="order-address" name="service[${changeService}][${arr[input].id}]" id="${m_id}address${arr[input].id}" name="${arr[input].name}">
                            </div>
                            `;
                        appendArea.append(htmlAddress);
                        if (!addressList[index]) {
                            addressList.push({
                                input: [m_id + "address" + arr[input].id],
                                complete: [null],
                                geometry: [{
                                    text: '',
                                    lat: null,
                                    lng: null
                                }],
                                directionsService: null,
                                directionsDisplay: null,
                                marker: null,
                                core: null
                            });
                        } else {
                            addressList[index].input.push(m_id + "address" + arr[input].id);
                            addressList[index].complete.push(null);
                            addressList[index].geometry.push({
                                text: '',
                                lat: null,
                                lng: null
                            });
                        }
                        addressFound = true;
                    }
                }

                if (addressFound) {
                    $($this).append('<div class="w-100"><div id="map' + m_id + '" class="googleMap"></div></div>');
                }
                setTimeout(function () {
                    console.log('addreslist');
                    console.log(addressList);
                    addressList[index].input.map(function (m, i) {
                        let input = document.getElementById(m);
                        google.maps.event.addDomListener(input, 'keydown', function (event) {
                            if (event.keyCode === 13) {
                                event.preventDefault();
                            }
                        });
                        addressList[index].complete[i] = new google.maps.places.Autocomplete((input), {types: ['geocode']});
                        addressList[index].complete[i].addListener('place_changed', function () {
                            let place = addressList[index].complete[i].getPlace();
                            addressList[index].geometry[i].text = place.formatted_address;
                            if (place.geometry.location) {
                                addressList[index].geometry[i].lat = place.geometry.location.lat();
                                addressList[index].geometry[i].lng = place.geometry.location.lng();
                            }
                            let found = 0;
                            addressList[index].geometry.map(geo => {
                                if (geo.text !== '') found++;
                            });
                            if (found > 0) {
                                $($this).find("#map" + m_id).show();
                            }
                            if (found === 1) {
                                if (addressList[index].core !== null) {
                                    setMarker(addressList[index], true);
                                } else {
                                    initMap("map" + m_id, addressList[index])
                                }
                            } else if (found > 1) {
                                setMarker(addressList[index], false);
                                setDirection(addressList[index]);
                            } else {
                                $($this).find("#map" + m_id).hide();
                            }

                            postCalc();
                        });
                    });
                }, 100);

            }
        })
    }

    function initMap(mapId, addressList) {
        addressList.directionsDisplay = new google.maps.DirectionsRenderer({});
        addressList.directionsService = new google.maps.DirectionsService;
        addressList.core = new google.maps.Map(document.getElementById(mapId), {
            zoom: 16,
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: google.maps.ControlPosition.BOTTOM_RIGHT
            },
            mapTypeControl: false,
            streetViewControl: false,
            center: {lat: parseFloat(addressList.geometry[0].lat), lng: parseFloat(addressList.geometry[0].lng)}
        });
        addressList.marker = new google.maps.Marker({
            position: {lat: parseFloat(addressList.geometry[0].lat), lng: parseFloat(addressList.geometry[0].lng)},
            map: addressList.core
        });
        addressList.directionsDisplay.setMap(addressList.core);
    }

    function setMarker(addressList, action) {
        if (action) {
            if (addressList.marker !== null) {
                addressList.marker.setMap(null);
            }
            addressList.marker = new google.maps.Marker({
                position: {lat: parseFloat(addressList.geometry[0].lat), lng: parseFloat(addressList.geometry[0].lng)},
                map: addressList.core
            });
            addressList.core.setCenter(new google.maps.LatLng(parseFloat(addressList.geometry[0].lat), parseFloat(addressList.geometry[0].lng)));
        } else {
            addressList.marker.setMap(null);
        }
        addressList.directionsDisplay.setMap(addressList.core);
    }

    function setDirection(addressList) {
        let geometry_data = addressList.geometry;
        let geometry = [];
        geometry_data.map(lm => {
            if (lm.text !== '') {
                geometry.push(lm);
            }
        });
        let waypts = [];
        geometry.map((m, i) => {
            if (geometry.length !== (i + 1) && i !== 0) {
                waypts.push({
                    location: m.text,
                    stopover: true
                });
            }
        });
        addressList.directionsService.route({
            origin: {lat: parseFloat(geometry[0].lat), lng: parseFloat(geometry[0].lng)},
            waypoints: waypts,
            destination: {lat: parseFloat(geometry[geometry.length - 1].lat), lng: parseFloat(geometry[geometry.length - 1].lng)},
            travelMode: google.maps.TravelMode['DRIVING']
        }, (response, status) => {
            if (status === 'OK') {
                addressList.directionsDisplay.setDirections(response);

                console.log(response);

            } else {
                alert("something went wrong")
            }
        });

        console.log(addressList);
    }

    $('body').on('change', 'select,input', function (event) {
        if($(this).hasClass('order-address'))
        {
            event.preventDefault();
        }
        else
        {
            postCalc();
        }
    });

    $('body').on('mouseup', 'div.regulator-plus,div.regulator-minus', function(){
        setTimeout(function() {
            postCalc();
        }, 100);
    });

    function postCalc(){
        console.log("salam");
        console.log(addressList.directionsDisplay);
        var form = $('div.modal-service__body form.order-form').serializeArray();
        form.push({name: '_token', value: csrf});

        $.ajax({
            method: "POST",
            url: '{{ route('calculate') }}',
            data: form,
            success: function (res) {
                let total = res.total;

                total = Math.round(total * 100) / 100;

                $('[x-order-selected-service-price]').html(total);
                console.log(total);
            }
        });
    }

    $('[x-order-form]').submit(function (e) {
        e.preventDefault();
        e.stopPropagation();
        /*var formData = new FormData(this);
        console.log(formData);

        formData.append( '_token' , csrf );*/
        /*var formData = $('[x-order-form]').serializeArray();
        formData.push({'_token': csrf});
        var formdata2 = JSON.stringify(formData);
        console.log(formdata2);

        $.ajax({
            type: 'POST',
            url: '{{ route('service-inputs') }}',
            data: formdata2,
            cache : false ,
            contentType : false ,
            processData : false ,
            success: function (res) {
                console.log(res);
            }
        });*/

        const formData = new FormData(this);
        formData.append( '_token' , csrf );


        console.log(formData);

        fetch('{{ route('order') }}' , {
            method: 'post',
            body: formData
        }).then(function (res) {
            console.log(res);
        }).catch(function (err) {
            console.log(err);
        });

    });


</script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=places,geometry&key=AIzaSyA50XWgVLv-Ngl_8aHhc2ZYknY516xEqTg&language=az"></script>
