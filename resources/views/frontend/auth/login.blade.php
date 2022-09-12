@extends('frontend.layouts.master')

@section('page-title', 'Login')

@section('container')
<div class="row h-100">
    <div class="col-12 col-md-4" style="background: #F89673;">
      <div class="">
        <img src="{{ asset('frontend/img/elements/login-page.png') }}" />
      </div>
      <div class="text-center">
        <img width="200" src="{{ asset('frontend/img/elements/login-page-3.png') }}" />
      </div>
      <div class="text-left px-5 pb-0 mt-md-5">
        <h3>Working Capital</h3>
      </div>
      <p style="color: white;" class="text-left px-5 text-white">Cash is the lifeblood of your company. Tanda enables you to boost your working capital efficiency, reducing the need for borrowing and releasing pressure on cash flows by generating capital via payroll.</p>
      <br>
      <p style="color: white;" class="text-left p-4 pt-0">
        <img width="300" src="{{ asset('frontend/img/elements/leftbar.png') }}" />
      </p>
    </div>
    <div class=" col-md-2"></div>
    <div class="col-12 col-md-4">
      <div class="  mt-md-5 authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <h4 class="mb-2">Welcome back</h4>
          <p class="mb-4 mt-3 text-dark">Login to your Tanda account</p>
          <form id="formAuthentication" class="mb-3" action="{{ route('auth.login.user') }}" method="POST"> 
            @csrf 
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
              @if ($errors->has('email'))
                <div class="error-message">{{ $errors->first('email') }}</div>
              @endif
            </div>

            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer">
                  <i class="text-dark bx bx-hide"></i>
                </span>
              </div>
              @if ($errors->has('password'))
                <div class="error-message">{{ $errors->first('password') }}</div>
              @endif
              <a class="justify-content-right text-dark" href="#" style="float:right;">
                <small>Forgot Password?</small>
              </a>
            </div>
            <br>
            <div class="mb-3">
              {{-- <a class="btn btn-primary d-grid w-100" href="overview.php">
                <span>Sign in</span>
              </a> --}}
              <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
              <!-- <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button> -->
            </div>
          </form>
          <div class="divider">
            <div class="divider-text text-dark">or</div>
          </div>
          <div class="text-center">
            <a class="btn btn-google w-100 btn-block text-uppercase  text-dark btn-outline" href="#">
              <img src="https://img.icons8.com/color/16/000000/google-logo.png"> Login with Google </a>
          </div>
          <br>
          <p class="text-left text-dark ">
            <span class="text-muted">Don’t have a Tanda account? </span>
            <a href="{{ route('register.get') }}" class="text-dark">
              <span>Sign up</span>
            </a>
          </p>
        </div>
      </div>
    </div>
    <div class=" col-md-2"></div>
</div>
@endsection

@push('scripts')
<script>
    $('button[type="submit"]').on('click', function(e){
        e.preventDefault();
        $(this).prop('disbaled', true);
        $(this).html('Processing ....');
        setTimeout(() => {
            $('#formAuthentication').submit();
        }, 1500);
    });
</script>    
@endpush