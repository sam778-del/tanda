@extends('frontend.layouts.master')

@section('page-title', 'Entity detail')

@section('container')
<div class="row h-100">
    <div class="col-12 col-md-4" style="background: #004652;">
      <div class="">
        <img src="./assets/img/elements/signup.png" />
      </div>
      <div class="text-center">
        <img width="200" src="./assets/img/elements/login-page-3.png" />
      </div>
      <div class="text-left px-5 pb-0 mt-md-5 ">
        <h3 style="color: #F89673;">Faster Pay</h3>
      </div>
      <p class="text-left px-5 text-white">94% of your employees worry about money. Offering more frequent pay has the potential to attract and retain talent, enhance employee wellbeing and improve productivity </p>
      <br>
      <p class="text-left p-4 pt-0">
        <img width="300" src="./assets/img/elements/leftbarblue.png" />
      </p>
    </div>
    <div class=" col-md-1"></div>
    <div class="col-12 col-md-5">
      <div class="  mt-md-5 authentication-basic container-p-y">
        <nav aria-label="breadcrumb " class="mb-5 small">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active">
              <i class='bx bxs-check-circle'></i>
              <label class="text-dark">Create acount </label>
            </li>
            <li class="breadcrumb-item active">
              <i class='bx bx-check-circle '></i>
              <label class="text-dark">Entity details</label>
            </li>
            <li class="breadcrumb-item ">
              <i class='bx bx-check-circle text-dark'></i>
              <label class="text-dark">Personal details</label>
            </li>
            <li class="breadcrumb-item ">
              <i class='bx bx-check-circle text-dark'></i>
              <label class="text-dark">Pay Schedule</label>
            </li>
          </ol>
        </nav>
        <div class="authentication-inner">
          <!-- Register -->
          <label class="mb-4">
            <i class='bx bx-left-arrow-alt'></i> Back </label>
          <h4 class="mb-2 text-dark">Entity details</h4>
          <p class="mb-4 mt-4 text-dark">To get stated please provide your company information accurately, it will be used for all your documents on Tanda.</p>
          <form id="formAuthentication" class="mb-3" action="index.html" method="POST">
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Email</label> -->
              <input type="text" class="form-control text-dark" id="legalentityname" name="legalentityname" placeholder="Legal entity name" autofocus />
            </div>
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Email</label> -->
              <select class="form-control form-select text-dark" id="Entitytype">
                <option value="">Entity type</option>
                <option value="">Incorporated Trustees</option>
                <option value="">Incorporated company</option>
                <option value="">Business Name Registration</option>
              </select>
            </div>
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Email</label> -->
              <input type="text" class="form-control text-dark" id="Registrationnumber" name="Registrationnumber" placeholder="Registration number " autofocus />
            </div>
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Email</label> -->
              <input type="text" class="form-control text-dark" id="VATID" name="VATID" placeholder="VAT ID (optional) " autofocus />
            </div>
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Email</label> -->
              <input type="text" class="form-control text-dark" id="Street" name="Street" placeholder="Street" autofocus />
            </div>
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Email</label> -->
              <input type="text" class="form-control text-dark" id="City" name="City" placeholder="City" autofocus />
            </div>
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Email</label> -->
              <input type="text" class="form-control text-dark" id="State" name="State" placeholder="State" autofocus />
            </div>
            <div class="mb-3">
              <a href="signuppersonaldetail.php" class="btn btn-primary d-grid w-100">Next</a>
              <!-- <button class="btn btn-primary d-grid w-100" type="submit">Next</button> -->
            </div>
          </form>
          <!-- /Register -->
        </div>
      </div>
    </div>
    <div class=" col-md-2  ">
      <a class="text-right mt-md-5 text-right  btn btn-sm btn-outline-warning">
        <i class="bx bx-log-out-circle"></i>Log out </a>
    </div>
  </div>
  </div>
@endsection