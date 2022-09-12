@extends('layouts.app')

@push('stylesheets')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
    <!-- Plugins css Ends-->
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
            <h3>Wallet History</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active"> Wallet History</li>
                    <li class="breadcrumb-item active"> {{ $user->first_name.' '.$user->last_name}} </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="dt-ext table-responsive">
                    <table class="display" id="export-button">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>User Name</th>
                          <th>Smart Goal</th>
                          <th>Status</th>
                          <th class="text-center">Initial Amount</th>
                          <th class="text-center">Actual Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($history as $key => $histories)
                            <?php
                                $smart_goal = \App\Models\SavingGoal::find($histories->smart_goal_id);
                                $smart_rule = \DB::table('users')
                            ?>

                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                <td>{{ $smart_goal->name }}</td>
                                <td>
                                    @if($histories->status === 'pending')
                                        <span class="btn btn-danger-gradien" title="btn btn-dark-gradien">{{ $histories->status }}</span>
                                    @else
                                        <span class="btn btn-success-gradien" title="btn btn-secondary-gradien">{{ $histories->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center text-primary">{{ number_format($histories->initial_amount) }}</td>
                                <td class="text-center text-primary">{{ number_format($histories->actual_amount) }}</td>
                            </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Smart Goal</th>
                                <th>Status</th>
                                <th class="text-center">Initial Amount</th>
                                <th class="text-center">Actual Amount</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/custom.js') }}"></script>
    <!-- Plugins JS Ends-->
@endpush
