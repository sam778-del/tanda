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
            <h3>Smart Rule Category</h3>
            </div>
            <div class="col-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Smart Rule Category</li>
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
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-center">Status</th>
                    @if(auth()->user()->can('Edit Smart Rule Management'))
                        <th class="text-center">Action</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                    @foreach ($category as $key => $cat)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $cat->name }}</td>
                            <td>{{ Str::limit($cat->descrition, 100) }}</td>
                            <td>
                                <span class="btn btn-primary-gradien" title="btn btn-secondary-gradien">{{ $cat->status }}</span>
                            </td>
                            <td class="text-center">
                                @can('Edit Smart Rule Management')
                                    <a class="btn btn-dark-gradien" href="{{ route('customer.profile', $goal->id) }}" title="Smart Rule Management">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    @if($goal->status !== 'Active')
                                        <a href="#" class="btn btn-danger-gradien swa-confirm" title="Disable Status"
                                            data-confirm="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-title="{{ __('Are You Sure?') }}" data-confirm-yes="document.getElementById('status-form-{{ $goal->id }}').submit();"
                                        >
                                            <i class="fa fa-lock"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-success-gradien swa-confirm" href="#" title="Enable Status"
                                            data-confirm="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-title="{{ __('Are You Sure?') }}" data-confirm-yes="document.getElementById('status-form-{{ $goal->id }}').submit();"
                                        >
                                            <i class="fa fa-unlock"></i>
                                        </a>
                                    @endif
                                    {!! Form::open(['method' => 'PUT', 'route' => ['goal.status', $goal->id], 'id' => 'status-form-' . $goal->id]) !!}
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
                        <th>Description</th>
                        <th class="text-center">Status</th>
                        @if(auth()->user()->can('Edit Smart Rule Management') || auth()->user()->can('Delete Customer'))
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
