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
            <h3>Customer List</h3>
            </div>
            <div class="col-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active"> Customer</li>
            </ol>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Customer List</h5>
          </div>
          <div class="card-body">
            <div class="dt-ext table-responsive">
              <table class="display" id="export-button">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    @can('Manage Bank Account')
                        <th class="text-center">Bank Acounts</th>
                    @endcan
                    @can('Manage Bank Statement')
                        <th class="text-center">Bank Statement</th>
                    @endcan
                    <th class="text-center">Status</th>
                    @if(auth()->user()->can('Manage Bill Payment') || auth()->user()->can('Manage Bill Transaction'))
                        <th class="text-center">Bill Action</th>
                    @endif
                    @if(auth()->user()->can('Edit Customer') || auth()->user()->can('Delete Customer'))
                        <th class="text-center">Action</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                    @foreach ($customer as $key => $customers)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $customers->first_name.' '.$customers->last_name}}</td>
                            <td>{{ $customers->email }}</td>
                            <td>{{ $customers->phone_no }}</td>
                            @can('Manage Bank Account')
                                <td class="text-center">
                                    <a class="btn btn-info-gradien" href="{{ route('bank.account', $customers->id) }}" title="Bank Accounts">
                                        <i data-feather="credit-card"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('Manage Bank Statement')
                                <td class="text-center">
                                    <a class="btn btn-primary-gradien" href="{{ route('bank.statement', $customers->id) }}" title="Bank Accounts">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            @endcan
                            <td>
                                @if($customers->status === 'DISABLE')
                                    <span class="btn btn-danger-gradien" title="btn btn-secondary-gradien">Deactive</span>
                                @else
                                    <span class="btn btn-success-gradien" title="btn btn-secondary-gradien">Active</span>
                                @endif
                            </td>
                            @if(auth()->user()->can('Manage Bill Payment') || auth()->user()->can('Manage Bill Transaction'))
                                <td class="text-center">
                                    <a class="btn btn-dark-gradien" href="{{ route('user.bill', array($customers->id, $customers->first_name.$customers->last_name)) }}" title="View User Bill Transaction">
                                        View
                                    </a>
                                </td>
                            @endif
                            <td class="text-center">
                                @can('Edit Customer')
                                    <a class="btn btn-secondary-gradien" href="{{ route('customer.profile', $customers->id) }}" title="Edit Profile">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                @endcan

                                @can('Delete Customer')
                                    @if($customers->status === 'DISABLE')
                                        <a href="#" class="btn btn-danger-gradien swa-confirm" title="Disable Customer"
                                            data-confirm="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-title="{{ __('Are You Sure?') }}" data-confirm-yes="document.getElementById('status-form-{{ $customers->id }}').submit();"
                                        >
                                            <i class="fa fa-lock"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-success-gradien swa-confirm" href="#" title="Disable Customer"
                                            data-confirm="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-title="{{ __('Are You Sure?') }}" data-confirm-yes="document.getElementById('status-form-{{ $customers->id }}').submit();"
                                        >
                                            <i class="fa fa-unlock"></i>
                                        </a>
                                    @endif
                                    {!! Form::open(['method' => 'PUT', 'route' => ['customer.status', $customers->id], 'id' => 'status-form-' . $customers->id]) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        @can('Manage Bank Account')
                            <th class="text-center">Bank Acounts</th>
                        @endcan
                        @can('Manage Bank Statement')
                            <th class="text-center">Bank Statement</th>
                        @endcan
                        <th class="text-center">Status</th>
                        @if(auth()->user()->can('Edit Customer') || auth()->user()->can('Delete Customer'))
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
