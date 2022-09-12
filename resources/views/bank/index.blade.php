@extends('layouts.app')

@push('stylesheets')
<!-- Plugins css start-->
<!-- Plugins css Ends-->
@endpush

@section('content')
<div class="container-fluid">
   <div class="page-title">
      <div class="row">
         <div class="col-6">
            <h3>Bank Account List for {{ $user->first_name.' '.$user->last_name }}</h3>
         </div>
         <div class="col-6">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <a href="{{ url('/dashboard') }}">
                  <i data-feather="home"></i>
                  </a>
               </li>
               <li class="breadcrumb-item">Dashboard</li>
               <li class="breadcrumb-item active"> Bank Account</li>
               <li class="breadcrumb-item active">{{ $user->first_name.' '.$user->last_name }} </li>
            </ol>
         </div>
      </div>
   </div>
   <!-- Container-fluid starts-->
   <div class="container-fluid">
      <div class="row project-cards">
         <div class="col-sm-12">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                      @forelse ($bank as $key => $banks)
                        @php
                            $bank_name = \App\Models\Bank::where('bank_code', $banks->bank_code)->first();
                        @endphp
                        <div class="col-xxl-4 col-lg-6">
                            <div class="project-box">
                            <h6>{{ empty($bank_name) ? 'Null' : $bank_name->bank_name }}</h6>
                            <div class="row details">
                                <div class="col-6"><span>Account Name </span></div>
                                <div class="col-6 text-primary">{{ $banks->account_name }} </div>
                                <div class="col-6"> <span>Account Number</span></div>
                                <div class="col-6 text-primary">{{ $banks->account_number }}</div>
                                <div class="col-6"> <span>Bank Code</span></div>
                                <div class="col-6 text-primary">{{ $banks->bank_code }}</div>
                            </div>
                            <div class="project-status mt-4 text-center">
                                <div class="media mb-0">
                                    <div class="text-center">
                                        <a class="btn btn-info-gradien" href="{{ route('bank.transactions', [empty($bank_name) ? 'Null' : $bank_name->bank_name, $banks->id]) }}" title="View Transactions">
                                            <i data-feather="credit-card"></i>
                                            <span>Transactions</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                      @empty
                        <div class="col-lg-12">
                            <p class="text-center text-danger">No Bank Acccount Found</p>
                        </div>
                      @endforelse
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Container-fluid Ends-->
</div>
@endsection

@push('scripts')
@endpush
