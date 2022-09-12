@extends('layouts.app')

@push('stylesheets')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/datatables.css">
    <!-- Plugins css Ends-->
@endpush

@section('content')
<div class="container-fluid">
    <div class="row project-cards">
        <div class="col-md-12 project-list">
          <div class="card">
            <div class="row">
              <div class="col-md-6">
              </div>
              <div class="col-md-6">
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Edit Role</h5>
          </div>
          <div class="card-body">
            {{ Form::model($role, ['route' => ['role.edit', $role->id], 'method' => 'PUT']) }}
                @csrf
                <div class="mb-3">
                  <label class="col-form-label">Name:</label>
                  <input class="form-control" name="name" type="text" value="{{ $role->name }}">
                </div>
                @if(!empty($permissions) && count($permissions) > 0)
                    {{ Form::label('permission', __('Assign Permission'), ['class' => 'form-label form-control-label']) }}

                    <div class="custom-control custom-checkbox float-right">
                        {{ Form::checkbox('select-all', false, null, ['class' => 'custom-control-input', 'id' => 'select-all']) }}
                        {{ Form::label('select-all', 'Select All', ['class' => 'custom-control-label form-control-label']) }}
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="table-responsive shadow-none">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="200px">{{__('Module')}}</th>
                                            <th class="text-center">{{__('Permissions')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $modules = ['User', 'Role', 'Profile', 'Customer', 'Bank Account', 'Bank Statement', 'Global Setting', 'Email', 'Notification', 'Sms', 'Savings Goal Management', 'Smart Rule Management', 'Salary Advance Management', 'Saving Transaction', 'Bill Payment', 'Bill Transaction'];
                                        ?>
                                        @foreach($modules as $module)
                                            <tr>
                                                <td class="form-control-label">{{__($module)}}</td>

                                                <td>
                                                    <div class="row">
                                                        <?php
                                                        $internalPermission = ['Manage', 'Create', 'Edit', 'Delete']
                                                        ?>
                                                        @foreach($internalPermission as $ip)
                                                            @if(in_array($ip . ' ' . $module,$permissions))
                                                                @php($key = array_search($ip . ' ' . $module, $permissions))
                                                                <div class="col-3 custom-control custom-checkbox">
                                                                    {{ Form::checkbox('permissions[]',$key,$role->permissions,['class' => 'custom-control-input','id'=>'permission_'.$key]) }}
                                                                    {{ Form::label('permission_'.$key, $ip ,['class'=>'custom-control-label ']) }}

                                                                </div>
                                                            @endif
                                                        @endforeach

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">Edit</button>
                  </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#select-all', function (e) {
            if (this.checked) {
                $(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                });
            }
        });
    </script>
    <!-- Plugins JS Ends-->
@endpush
