<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel2">New Staff</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('staff.edit', $user->id) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="col-form-label">Name:</label>
              <input class="form-control" name="name" type="text" value="{{ $user->name }}">
            </div>
            <div class="mb-3">
                <label class="col-form-label">Email:</label>
                {!! Form::email('email', $user->email, ["class" => "form-control"]) !!}
            </div>
            <div class="mb-3">
                <label class="col-form-label">Role:</label>
                {!! Form::select('role', $role, null, ["class" => "form-control select2"]) !!}
            </div>
            <div class="mb-3">
                <label class="col-form-label">Password:</label>
                {!! Form::password('password', ["class" => "form-control"]) !!}
            </div>
            <div class="mb-3">
                <label class="col-form-label">Confirm Password:</label>
                {!! Form::password('password_confirmation', ["class" => "form-control"]) !!}
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit">Edit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
