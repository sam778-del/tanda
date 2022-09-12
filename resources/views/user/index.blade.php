@extends('layouts.app')

@push('stylesheets')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
    <!-- Plugins css Ends-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row project-cards">
            <div class="col-md-12 project-list">
              <div class="card">
                <div class="row">
                  <div class="col-md-6">
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createModal" data-whatever="">
                        <i data-feather="plus-square"> </i>Create Staff
                    </button>
                    @include('user.create')
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Staff List</h5>
          </div>
          <div class="card-body">
            <div class="dt-ext table-responsive">
              <table class="display" id="export-button">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="text-center">Status</th>
                    @if(auth()->user()->can('Edit User') || auth()->user()->can('Delete User'))
                        <th class="text-center">Action</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                    @foreach ($admin as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @can('Edit User')
                                    @if($user->status == 0)
                                        <a href="#" class="btn btn-danger-gradien swa-confirm" title="Disable User"
                                            data-confirm="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-title="{{ __('Are You Sure?') }}" data-confirm-yes="document.getElementById('status-form-{{ $user->id }}').submit();"
                                        >
                                            <i class="fa fa-lock"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-success-gradien swa-confirm" href="#" title="Disable User"
                                            data-confirm="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-title="{{ __('Are You Sure?') }}" data-confirm-yes="document.getElementById('status-form-{{ $user->id }}').submit();"
                                        >
                                            <i class="fa fa-unlock"></i>
                                        </a>
                                    @endif
                                    {!! Form::open(['method' => 'PUT', 'route' => ['user.status', $user->id], 'id' => 'status-form-' . $user->id]) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                            <td>
                                @can('Edit User')
                                    <a class="btn btn-primary-gradien" href="javascript:void(0);" title="Edit User"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}" data-whatever=""
                                    >
                                        <i data-feather="edit"></i>
                                    </a>
                                    @include('user.edit')
                                @endcan

                                @can('Delete User')
                                    @if(auth()->user()->id !== $user->id)
                                        <a class="btn btn-danger-gradien swa-confirm" href="javascript:void(0);" title="Delete User"
                                            data-confirm="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-title="{{ __('Are You Sure?') }}" data-confirm-yes="document.getElementById('delete-user-form-{{ $user->id }}').submit();"
                                        >
                                            <i data-feather="delete"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['user.delete', $user->id], 'id' => 'delete-user-form-' . $user->id]) !!}
                                        {!! Form::close() !!}
                                    @endif
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
                        <th class="text-center">Status</th>
                        @if(auth()->user()->can('Edit User') || auth()->user()->can('Delete User'))
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

    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script>
        "use strict";
        setTimeout(function(){
            (function($) {
                "use strict";
                // Single Search Select
                $(".js-example-basic-single").select2();
            })(jQuery);
        }
        ,350);
    </script>
@endpush
