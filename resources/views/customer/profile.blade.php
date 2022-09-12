@extends('layouts.app')

@push('stylesheets')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css') }}">
    <!-- Plugins css Ends-->
@endpush

@php
    $defaultImage = 'avatar/avatar.png';
    $avatar = $user->image !== NULL ? asset(Storage::url($user->image)) : asset('avatar/avatar.png');
@endphp

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
            <h3>Edit Profile</h3>
            </div>
            <div class="col-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active"> Edit Profile</li>
            </ol>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="edit-profile">
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title mb-0">My Profile</h4>
                  </div>
                  <div class="card-body">
                    {!! Form::open(["route" => ["customer.update", $user->id], "method" => "PUT"]) !!}
                        @csrf
                      <div class="row mb-2">
                        <div class="profile-title">
                          <div class="media">
                              <img class="img-70 rounded-circle" alt="{{ $user->first_name.' '.$user->last_name}}" src="{{ $avatar }}">
                            <div class="media-body">
                              <h5 class="mb-1">{{ $user->first_name.' '.$user->last_name}}</h5>
                              <p>Customer</p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input class="form-control" name="first_name" placeholder="First Name" value="{{ $user->first_name }}">
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input class="form-control" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}">
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Email-Address</label>
                        <input class="form-control" name="email" placeholder="your-email@domain.com" value="{{ $user->email }}" readonly>
                      </div>
                      <div class="form-footer">
                        <button class="btn btn-primary btn-block" type="submit">Save</button>
                      </div>
                    {!! Form::close() !!}
                  </div>
                </div>
              </div>
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title mb-0">Update Avatar</h4>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                        <div class="mb-3">
                            <label>Upload project file</label>
                            <form class="dropzone" id="singleFileUpload" action="{{ route('update.avatar', $user->id) }}">
                                @csrf
                                <div class="dz-message needsclick">
                                    <i class="icon-cloud-up"></i>
                                    <h6>Drop files here or click to upload.</h6>
                                </div>
                            </form>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title mb-0">Recent Transactions</h4>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                  </div>
                  <div class="table-responsive add-project">
                    <table class="table card-table table-vcenter text-nowrap">
                      <thead>
                          @if(count($transaction) > 0)
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Price</th>
                            </tr>
                          @endif
                      </thead>
                      <tbody>
                          @forelse ($transaction as $key => $transactions)
                            <tr>
                                <td><a class="text-inherit">{{ $key + 1 }} </a></td>
                                <td>{{ date('Y M D', strtotime($transactions->created_at)) }}</td>
                                <td>
                                    @if($transactions->status == 'pending')
                                        <span class="btn btn-warning-gradien" title="btn btn-secondary-gradien">Pending</span>
                                    @elseif ($transactions->status == 'success')
                                        <span class="btn btn-success-gradien" title="btn btn-secondary-gradien">Success</span>
                                    @elseif ($transactions->status == 'failed')
                                        <span class="btn btn-danger-gradien" title="btn btn-secondary-gradien">Failed</span>
                                    @else
                                        <span class="btn btn-info-gradien" title="btn btn-secondary-gradien">Reversed</span>
                                    @endif
                                </td>
                                <td>{{ $transactions->amount }}</td>
                            </tr>
                          @empty
                            <div class="text-center">
                                <p class="text-danger">{{ __('No Transaction Found') }}</p>
                            </div>
                          @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
    <!-- Plugins JS Ends-->
@endpush
