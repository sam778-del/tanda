<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel2">New Role</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('role.create') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="col-form-label">Name:</label>
              <input class="form-control" name="name" type="text">
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
                                                                {{ Form::checkbox('permissions[]',$key,false,['class' => 'custom-control-input','id'=>'permission_'.$key]) }}
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
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit">Create</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
