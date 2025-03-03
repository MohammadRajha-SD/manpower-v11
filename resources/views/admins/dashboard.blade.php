<x-admin-layout>
    <x-admins.cards.header :name="__('lang.dashboard')" :desc="__('lang.dashboard_overview')"
        :route="route('admin.dashboard')" />

    <div class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            {{-- BOOKING --}}
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-white shadow-sm">
                    <div class="inner">
                        <h3 class="text-{{setting('theme_color','primary')}}">{{$booking_counted}}</h3>

                        <p>{{trans('lang.dashboard_total_bookings')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="{{route('admin.bookings.index')}}"
                        class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- EARNING --}}
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-white shadow-sm">
                    <div class="inner">
                        @if(setting('currency_right', false) != false)
                        <h3 class="text-{{setting('theme_color','primary')}}">
                            {{$earning}}{{setting('default_currency')}}</h3>
                        @else
                        <h3 class="text-{{setting('theme_color','primary')}}">
                            {{setting('default_currency')}}{{$earning}}</h3>
                        @endif

                        <p>{{trans('lang.dashboard_total_earnings')}} <span
                                style="font-size: 11px">({{trans('lang.dashboard_after_taxes')}})</span></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    {{-- TODO: {{route('earnings.index')}} --}}
                    <a href="" class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- PROVIDERS --}}
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-white shadow-sm">
                    <div class="inner">
                        <h3 class="text-{{setting('theme_color','primary')}}">{{$providers_counted}}</h3>
                        <p>{{trans('lang.e_provider_plural')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <a href="{{route('admin.providers.index')}}"
                        class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            {{-- MEMBERS --}}
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-white shadow-sm">
                    <div class="inner">
                        <h3 class="text-{{setting('theme_color','primary')}}">{{$members_counted}}</h3>

                        <p>{{trans('lang.dashboard_total_customers')}}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    {{-- {!! route('admin.users.index') !!} --}}
                    <a href="" class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{trans('lang.earning_plural')}}</h3>
                            <a
                                href="{{route('admin.payments.index')}}">{{trans('lang.dashboard_view_all_payments')}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                @if(setting('currency_right', false) != false)
                                <span class="text-bold text-lg">{{$earning}}{{setting('default_currency')}}</span>
                                @else
                                <span class="text-bold text-lg">{{setting('default_currency')}}{{$earning}}</span>
                                @endif
                                <span>{{trans('lang.dashboard_earning_over_time')}}</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success"> {{$booking_counted}}</span></span>
                                <span class="text-muted">{{trans('lang.dashboard_total_bookings')}}</span>
                            </p>
                        </div>
                        <!-- /.d-flex -->

                        <div class="position-relative mb-4">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2"> <i class="fas fa-square text-primary"></i>
                                {{trans('lang.dashboard_this_year')}} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts_lib')
    <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>
    @endpush
    @push('scripts')
    <script type="text/javascript">
        var data = [1000, 2000, 3000, 2500, 2700, 2500, 3000];
        var labels = ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

        function renderChart(chartNode, data, labels) {
            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            };

            var mode = 'index';
            var intersect = true;
            return new Chart(chartNode, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            data: data
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,

                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    @if(setting('currency_right', '0') == '0')
                                        return "{{setting('default_currency')}} "+value;
                                    @else
                                        return value+" {{setting('default_currency')}}";
                                    @endif

                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        }

        $(function () {
        'use strict'

        var $salesChart = $('#sales-chart')

        $.ajax({
            url: "{!! route('admin.payments.byMonth') !!}",
            method: 'GET',
            success: function (result) {
                if(result.status == 'success'){
                    $("#loadingMessage").html("");
                    var data = result.data;
                    var labels = result.labels;
                    renderChart($salesChart, data, labels)
                }
            },
            error: function (err) {
                $("#loadingMessage").html("Error loading data");
            }
        });
    }) 
    </script>
    @endpush
</x-admin-layout>