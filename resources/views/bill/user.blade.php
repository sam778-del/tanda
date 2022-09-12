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
            <h3>Bill Trasaction List</h3>
            </div>
            <div class="col-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active"> Bill Transaction</li>
            </ol>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Bill Transaction List</h5>
          </div>
          <div class="card-body">
            <div class="dt-ext table-responsive">
              <table class="display" id="export-button">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Bill Name</th>
                    <th>Payment Method</th>
                    <th class="text-center">Image</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Narration</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($billTransaction as $key => $bill)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $bill->user->first_name.' '.$bill->user->first_name }}
                            <td>{{ $bill->billPayment->name }}</td>
                            <td>{{ $bill->payment_method }}</td>
                            <td class="text-center">
                                <img class="rounded-square" height="80" src="{{ $bill->billPayment->image }}" alt="">
                            </td>
                            <td class="text-center">{{ number_format($bill->amount) }}</td>
                            <td class="text-center">{{ $bill->narration }}</td>
                            <td class="text-center">
                                @if($bills->status === 'pending')
                                    <span class="btn btn-warning-gradien" title="btn btn-secondary-gradien">{{ $bills->status }}</span>
                                @elseif ($bills->status === 'failed')
                                    <span class="btn btn-danger-gradien" title="btn btn-secondary-gradien">{{ $bills->status }}</span>
                                @elseif ($bills->status === 'reversed')
                                    <span class="btn btn-danger-gradien" title="btn btn-dark-gradien">{{ $bills->status }}</span>
                                @else
                                    <span class="btn btn-success-gradien" title="btn btn-secondary-gradien">{{ $bills->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Bill Name</th>
                        <th>Payment Method</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Narration</th>
                        <th class="text-center">Status</th>
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
