@extends('frontend.layouts.app')

@php
 $employerName = Str::limit(Auth::guard('employer')->user()->last_name, 4, '...');
@endphp

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-12 col-md-8 col-lg-8 order-3 order-md-2">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-6 mb-4  ">
          <div class="card h-100  " style="background: linear-gradient(180deg, #FFC7B4 0%, #F89673 100%); color:aliceblue;">
            <div class="card-body">
              <div class="card-title">
                <h3 style="color: white;" class="mt-md-6 mb-0"> Welcome back, {{ ucfirst($employerName) }}</h3>
                <label class="text-center" style="width:45%; border-bottom: 2px solid rgb(255, 255, 255); margin-left:80px"></label>
                <p class="mt-2">Here’s what’s new in everyday software</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <h4> Upcoming Payroll</h4>
              </div>
              <hr>
              <div class="row mt-md-3 text-dark">
                <div class="col-12 text-center text-muted">
                  <img class="w-25" src="{{ asset('frontend/img/elements/payrollimg.png') }}">
                  <br>
                  <p class="mt-2">No payroll configured yet</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12 text-center">
                  <a href="runpayrolltab.php"> Onboard employees <i class='bx bx-right-arrow-alt'></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <h4> My Company</h4>
              </div>
              <hr>
              <div class="row">
                <div class="col-12 text-dark text-center text-muted">
                  <p> There are no employees onboarded yet. You can either bulk-upload or invite single employees</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12 text-center">
                  <a class="label-pink " href="employees.php"> Setup Employes <i class='bx bx-right-arrow-alt'></i>
                    <br>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <h4> Payroll Wallet</h4>
              </div>
              <hr>
              <div class="row text-center text-dark mt-2">
                <div class="col-12">
                  <label class="mx-auto"> Account Balance </label>
                  <br>
                  <label>
                    <h1>₦0.00 </h1>
                  </label>
                </div>
              </div>
              <hr class="mx-auto">
              <div class="row  ">
                <div class="col-12  text-center">
                  <a href="employees.php" class="label-pink "> Fund Balance <i class='bx bx-right-arrow-alt'></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- </div><div class="row"> -->
        <!-- Payment History -->
        <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2">
          <div class="card">
            <div class="row row-bordered g-0">
              <div class="col-md-12">
                <h5 class="card-header m-0 me-2 ">Payment History</h5>
              </div>
              <div class="row mt-md-3 p-5 text-dark">
                <div class="col-12 text-center text-muted">
                  <img src="{{ asset('frontend/img/elements/paymenthistory.png') }}">
                  <br>
                  <p class="mt-2">You will see beautiful graph after your first payment!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--/ Payment History -->
      </div>
    </div>
    <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
      <div class="card  h-100">
        <div class="card-body ">
          <div class="card-title d-flex align-items-start justify-content-between">
            <h4> Pending Task </h4>
            <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20" style="margin-left: auto;">2</span>
          </div>
          <hr>
          <div class="row text-dark">
            <div class="col-1">
              <i class='bx bxs-file'></i>
            </div>
            <div class="col-9 ">
              <label> Setup your employees. Let's get all of your employeeson board to Tanda</label>
            </div>
            <div class="col-1">
              <a class="label-pink ">
                <i class='bx bx-right-arrow-alt'></i>
              </a>
            </div>
          </div>
          <hr>
          <div class="row text-dark">
            <div class="col-1">
              <i class='bx bxs-file'></i>
            </div>
            <div class="col-9">
              <label> Watch the product tour video</label>
            </div>
            <div class="col-1">
              <a class="label-pink ">
                <i class='bx bx-right-arrow-alt'></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
</div>
@endsection             