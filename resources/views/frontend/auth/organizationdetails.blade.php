@extends('frontend.layouts.master')

@section('page-title', 'Your organizations detail')

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
    <div class="col-12 col-md-4">
      <div class="  mt-md-5 authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <a href="register.php">
            <label class="mb-4">
              <i class='bx bx-left-arrow-alt'></i> Back </label>
          </a>
          <h4 class="mb-2 text-dark">Your organizations details</h4>
          <p class="mb-4 text-dark mt-md-4">Please provide your Organization details accurately, it will be used in all yours communications on the platforms</p>
          <form id="formAuthentication" class="mb-3" action="index.html" method="POST">
            <div class="mb-3 mt-md-4">
              <input type="text" class="form-control text-dark" id="email" name="email" placeholder="organization name" autofocus />
            </div>
            <div class="mb-3 mt-md-4">
              <!-- <label for="email" class="form-label">Email</label> -->
              <select class="form-control form-select text-dark" id="abouttando">
                <option value="">organization size</option>
                <option value="">1 - 50 team members</option>
                <option value="">51 - 200 team members</option>
                <option value="">201 - 300 team members</option>
                <option value=""> 2000+ team members</option>
              </select>
            </div>
            <div class="mb-3 mt-md-4">
              <a href="signupconfirmemail.php" class="btn btn-primary d-grid w-100">Next</a>
              <!-- <button href="signupconfirmemail.php" class="btn btn-primary d-grid w-100" type="submit">Next</button> -->
            </div>
          </form>
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
  <div class="buy-now">
    <a href="" target="_blank" class="btn btn-danger btn-buy-now">
      <i class='bx bx-message-square-minus'></i>
    </a>
  </div>
@endsection