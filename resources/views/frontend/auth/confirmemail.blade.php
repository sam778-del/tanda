@extends('frontend.layouts.master')

@section('page-title', 'Confirm Email')

@section('container')
<div class="row h-100">
    <div class="col-12 col-md-4" style="background: #F89673;">
      <div class="">
        <img src="./assets/img/elements/login-page.png" />
      </div>
      <div class="text-center">
        <img width="200" src="./assets/img/elements/login-page-3.png" />
      </div>
      <div class="text-left px-5 pb-0 mt-md-5">
        <h3>Working Capital</h3>
      </div>
      <p class="text-left px-5 text-white">Cash is the lifeblood of your company. Tanda enables you to boost your working capital efficiency, reducing the need for borrowing and releasing pressure on cash flows by generating capital via payroll.</p>
      <br>
      <p class="text-left p-4 pt-0 text-white">
        <img width="300" src="./assets/img/elements/leftbar.png" />
      </p>
    </div>
    <div class=" col-md-1"></div>
    <div class="col-12 col-md-6">
      <div class="  mt-md-5 authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <h3 class="mb-2 mt-md-6  text-dark">Check your inbox and <br> confirm your email address </h3>
          <p class="mb-4  text-dark">We have sent a confirmation email to info@prodegreeschool.com</p>
          <div class="alert p-5 alert-secondary text-dark " role="alert">
            <i class='bx bx-error-circle display-5'></i> &nbsp; <strong class="display-6"> Didn’t receive an email ?</strong>
            <br>
            <p class="  mt-2 text-dark" style="margin-left:40px;"> If you can’t find you email in your inbox <br> or spam folder, click below and we will <br>send you a new one </p>
            <a style="margin-left:40px;" class="  btn btn-sm btn-outline-warning">Resend email</a>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
    <div class=" col-md-3 text-right ">
      <label class="mt-md-5 small text-dark"></label>
      <a href="index.php" class=" text-right btn btn-sm btn-outline-warning">
        <i class="bx bx-log-out-circle"></i>Log out </a>
    </div>
  </div>
</div>
<div class="buy-now">
    <a href="" target="_blank" class="btn btn-danger btn-buy-now">
        <i class='bx bx-message-square-minus'></i>
    </a>
</div>
@endsection