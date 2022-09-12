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
            <h3>Transaction List for {{ Request::segment(2) }}</h3>
         </div>
         <div class="col-6">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <a href="{{ url('/dashboard') }}">
                  <i data-feather="home"></i>
                  </a>
               </li>
               <li class="breadcrumb-item">Dashboard</li>
               <li class="breadcrumb-item active"> Transaction </li>
               <li class="breadcrumb-item active">{{ Request::segment(2) }} </li>
            </ol>
         </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <div class="dt-ext table-responsive">
                <table class="display" id="export-button">
                    @if(count($transaction) > 0)
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Bank Name</th>
                            <th>Transfer Id</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Reference</th>
                            <th>Status</th>
                            </tr>
                        </thead>
                    @endif
                  <tbody>
                      @forelse ($transaction as $key => $transact)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ Request::segment(2) }}</td>
                            <td>{{ $transact->transfer_id }}</td>
                            <td>{{ $transact->amount }}</td>
                            <td>{{ $transact->fee }}</td>
                            <td>{{ $transact->reference }}</td>
                            <td class="text-center">
                                <span class="btn btn-success-gradien" title="btn btn-secondary-gradien">{{ $transact->status }}</span>
                            </td>
                        </tr>
                      @empty
                        <div class="text-center">
                            <p class="text-danger">No Transaction Found For {{ Request::segment(2) }}</p>
                        </div>
                      @endforelse
                  </tbody>
                  @if(count($transaction) > 0)
                   <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Bank Name</th>
                            <th>Transfer Id</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Reference</th>
                            <th>Status</th>
                        </tr>
                    </tfoot>
                 @endif
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
   </div>
   <!-- Container-fluid starts-->

   <!-- Container-fluid Ends-->
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
