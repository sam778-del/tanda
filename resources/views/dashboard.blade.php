@extends('layouts.app')

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Dashboard</h3>
                </div>
                <div class="col-6">
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row second-chart-list third-news-update">
            <div class="col-xl-4 col-lg-12 xl-50 morning-sec box-col-12">
                <div class="card o-hidden profile-greeting">
                    <div class="card-body" style="background: green">
                        <div class="media">
                            <div class="badge-groups w-100">
                            </div>
                        </div>
                        <div class="greeting-user text-center">
                            <div class="profile-vector"><img class="img-fluid"
                                                             src="{{ asset
                                                             ('assets/images/dashboard/cartoon.png')
                                                              }}"
                                                             alt=""></div>
                            <h4 class="f-w-600"><span id="greeting">Good Morning</span>
                                <span class="right-circle"><i
                                        class="fa fa-check-circle f-14 middle"></i></span>
                            </h4>
{{--                            <p>--}}
{{--                                <span> Today's earrning is $405 & your sales increase rate is 3.7 over the last 24 hours</span>--}}
{{--                            </p>--}}

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 xl-100 dashboard-sec box-col-12">
                <div class="card earning-card">
                    <div class="card-body p-0">
                        <div class="row m-0">
                            <div class="col-xl-12 p-0">
                                <div class="chart-right">
                                    <div class="row m-0 p-tb">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 p-0">
                                            <div class="inner-top-left">
                                                <p>
                                                    <h3>Transaction Report</h3>
                                                    <p>Last 14 Days Transaction</p>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card-body p-0">
                                                <div class="current-sale-container">
                                                    <div id="chart-currently"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border-top m-0">
                                    <div class="col-xl-4 ps-0 col-md-6 col-sm-6">
                                        <div class="media p-0">
                                            <div class="media-left">
                                                <i class="icofont icofont-cur-dollar"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6>{{ \Carbon\Carbon::now()->format('l') }} Transaction</h6>
                                                <p>{{ $todayTransaction }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-sm-6">
                                        <div class="media p-0">
                                            <div class="media-left bg-secondary">
                                                <i class="icofont icofont-cur-dollar"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6>{{ \Carbon\Carbon::now()->format('F') }} Transaction</h6>
                                                <p>{{ $monthlyTransaction }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-12 pe-0">
                                        <div class="media p-0">
                                            <div class="media-left">
                                                <i class="icofont icofont-cur-dollar"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6>Total Transaction</h6>
                                                <p>{{ $orderTransaction }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 xl-100 dashboard-sec box-col-12">
                <div class="card earning-card">
                    <div class="card-body p-0">
                        <div class="row m-0">
                            <div class="col-xl-12 p-0">
                                <div class="chart-right">
                                    <div class="row m-0 p-tb">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 p-0">
                                            <div class="inner-top-left">
                                                <p>
                                                    <h4>This Week Transaction Report</h4>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card-body">
                                                <div class="table-responsive add-project">
                                                    <table class="table card-table table-vcenter text-nowrap">
                                                      <thead>
                                                          @if(count($transaction) > 0)
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Date</th>
                                                                <th>Status</th>
                                                                <th>Price</th>
                                                            </tr>
                                                          @endif
                                                      </thead>
                                                      <tbody>
                                                          @forelse ($transaction as $key => $transactions)
                                                            <tr>
                                                                <td><a class="text-inherit">{{ $key + 1 }} </a></td>
                                                                <td>{{ date('Y M D', strtotime($transactions->created_at)) }}</td>
                                                                <td>
                                                                    @if($transactions->status == 'pending')
                                                                        <span class="btn btn-warning-gradien" title="btn btn-secondary-gradien">Pending</span>
                                                                    @elseif ($transactions->status == 'success')
                                                                        <span class="btn btn-success-gradien" title="btn btn-secondary-gradien">Success</span>
                                                                    @elseif ($transactions->status == 'failed')
                                                                        <span class="btn btn-danger-gradien" title="btn btn-secondary-gradien">Failed</span>
                                                                    @else
                                                                        <span class="btn btn-info-gradien" title="btn btn-secondary-gradien">Reversed</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $transactions->amount }}</td>
                                                            </tr>
                                                          @empty
                                                            <div class="text-center">
                                                                <p class="text-danger">{{ __('No Transaction Found') }}</p>
                                                            </div>
                                                          @endforelse
                                                      </tbody>
                                                    </table>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 xl-50 appointment-sec box-col-6">
                <div class="row">

                </div>
            </div>
            <div class="col-xl-4 col-lg-12 xl-50 calendar-sec box-col-6">
                <div class="card gradient-primary o-hidden">
                    <div class="card-body">
                        <div class="setting-dot">
                            <div class="setting-bg-primary date-picker-setting position-set pull-right">
                                <i class="fa fa-spin fa-cog"></i></div>
                        </div>
                        <div class="default-datepicker">
                            <div class="datepicker-here" data-language="en"></div>
                        </div>
                        <span class="default-dots-stay overview-dots full-width-dots"><span
                                class="dots-group"><span class="dots dots1"></span><span
                                    class="dots dots2 dot-small"></span><span
                                    class="dots dots3 dot-small"></span><span
                                    class="dots dots4 dot-medium"></span><span
                                    class="dots dots5 dot-small"></span><span
                                    class="dots dots6 dot-small"></span><span
                                    class="dots dots7 dot-small-semi"></span><span
                                    class="dots dots8 dot-small-semi"></span><span
                                    class="dots dots9 dot-small">                </span></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@push('scripts')
<script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
<script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
<script>
    $(function() {
        "use strict";
        var options = {
            chart: {
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#77B6EA', '#51cb97'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            title: {
                text: '',
                align: 'left'
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            markers: {
                size: 1
            },
            series: [{
                name: 'Transaction Report',
                data: {!! json_encode($getOrderChart['data'])  !!},
            }],

            xaxis: {
                categories: {!! json_encode($getOrderChart['label'])  !!},
                title: {
                    text: '{{ __("Days") }}'
                }
            },
            colors:[ CubaAdminConfig.primary , CubaAdminConfig.secondary ]
        }
        var chart = new ApexCharts(
            document.querySelector("#chart-currently"),
            options
        );
        chart.render();
    });
</script>
@endpush
