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
            <h3>Saving Transaction Management</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active"> Saving Transaction</li>
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
                      <th class="text-center">Amount</th>
                      <th>Narration</th>
                      <th>Status</th>
                      @if(auth()->user()->can('Manage Saving Transaction'))
                          <th class="text-center">Action</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($transaction as $key => $transactions)
                        <?php
                            $user = \App\Models\User::find($transactions->user_id);
                        ?>
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->first_name.' '.$user->last_name }}</td>
                            <td class="text-success text-center">{{ number_format($transactions->amount) }}</td>
                            <td>{{ $transactions->narration }}</td>
                            <td>
                                @if($transactions->status === 'pending')
                                    <span class="btn btn-warning-gradien" title="btn btn-secondary-gradien">{{ $transactions->status }}</span>
                                @elseif ($transactions->status === 'failed')
                                    <span class="btn btn-danger-gradien" title="btn btn-secondary-gradien">{{ $transactions->status }}</span>
                                @elseif ($transactions->status === 'reversed')
                                    <span class="btn btn-danger-gradien" title="btn btn-dark-gradien">{{ $transactions->status }}</span>
                                @else
                                    <span class="btn btn-success-gradien" title="btn btn-secondary-gradien">{{ $transactions->status }}</span>
                                @endif
                            </td>
                            @can('Manage Saving Transaction')
                                <td class="text-center">
                                    <a class="btn btn-primary-gradien" href="{{ route('saving.wallet', $transactions->savings_wallet_id) }}" title="View Saving Wallet">
                                        <i data-feather="eye"></i>
                                    </a>

                                    <a class="btn btn-info-gradien" href="{{ route('saving.history', array($transactions->user_id, $user->first_name.$user->last_name)) }}" title="View Wallet History">
                                        <i data-feather="credit-card"></i>
                                    </a>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th class="text-center">Amount</th>
                            <th>Narration</th>
                            <th>Status</th>
                            @if(auth()->user()->can('Manage Saving Transaction'))
                                <th class="text-center">Action</th>
                            @endif
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
