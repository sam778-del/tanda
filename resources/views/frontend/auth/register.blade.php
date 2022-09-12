@extends('frontend.layouts.master')

@section('page-title', 'Register')

@section('container')
<div class="row h-100">
    <div class="col-12 col-md-4" style="background: #004652;">
      <div class="">
        <img src="{{ asset('frontend/img/elements/signup.png') }}" />
      </div>
      <div class="text-center">
        <img width="200" src="{{ asset('frontend/img/elements/login-page-3.png') }}" />
      </div>
      <div class="text-left p-4 pt-0 mt-md-5 ">
        <h3 style="color: #F89673;">Faster Pay</h3>
      </div>
      <p class="text-left p-4 pt-0 text-white">94% of your employees worry about money. Offering more frequent pay has the potential to attract and retain talent, enhance employee wellbeing and improve productivity</p>
      <br>
      <p class="text-left p-4 pt-0">
        <img width="300" src="{{ asset('frontend/img/elements/leftbarblue.png') }}" />
      </p>
    </div>
    <div class=" col-md-1"></div>
    <div class="col-12 col-md-4">
      <div class="  mt-md-5 authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <a href="index.php">
            <label class="mb-4">
              <i class='bx bx-left-arrow-alt'></i> Back </label>
          </a>
          <h4 class="mb-2 text-dark">Sign up as an organization</h4>
          <p class="mb-4 mt-md-4 text-dark">Sign up with your google account or use the form</p>
          <form id="formAuthentication" class="mb-3" action="{{ route('auth.register.user') }}" method="POST">
            @csrf
            <div class="text-center">
              <a class="btn btn-google w-100 btn-block btn-outline text-dark" href="#">
                <img src="https://img.icons8.com/color/16/000000/google-logo.png"> Sign up with Google </a>
            </div>
            <br>
            <div class="divider">
              <div class="divider-text text-dark">or</div>
            </div>
            {{-- <div class="text-center spinner-border spinner-border-lg text-warning" role="status"><span class="visually-hidden">Loading...</span></div> --}}
            <div class="mb-3">
              <label for="first_name" class="form-label">First Name</label>
              <input type="text" class="form-control text-dark" id="first_name" name="first_name" placeholder=" First name" autofocus />
              @if ($errors->has('first_name'))
                <div class="error-message">{{ $errors->first('first_name') }}</div>
              @endif
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Last Name</label>
              <input type="text" class="form-control text-dark" id="lastname" name="last_name" placeholder=" Last name" autofocus />
              @if ($errors->has('last_name'))
                <div class="error-message">{{ $errors->first('last_name') }}</div>
              @endif
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control text-dark" id="email" name="email" placeholder="  email" autofocus />
              @if ($errors->has('email'))
                <div class="error-message">{{ $errors->first('email') }}</div>
              @endif
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control text-dark" name="password" placeholder="Password" aria-describedby="password" />
                <span class="input-group-text text-dark cursor-pointer">
                  <i class="bx bx-hide"></i>
                </span>
              </div>
              @if ($errors->has('password'))
                <div class="error-message">{{ $errors->first('password') }}</div>
              @endif
            </div>
            <div class="mb-3">
              <label for="know_us" class="form-label">How did you hear about Tanda?</label>
              <select class="form-control form-select text-dark" name="know_us" id="abouttando">
                <option value="Google">Google</option>
                <option value="Facebook">Facebook</option>
                <option value="Instagram">Instagram</option>
                <option value="Word of Mouth">Word of Mouth</option>
                <option value="Blog Post">Blog Post</option>
                <option value="In-Person Event">In-Person Event</option>
                <option value="Other">Other</option>
              </select>
              @if ($errors->has('know_us'))
                <div class="error-message">{{ $errors->first('know_us') }}</div>
              @endif
            </div>
            <!-- <a class="text-center" href="#"> &#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;
                  <small>Forgot Password?</small></a> -->
            <div class="mb-3">
              <button type="submit" class="btn btn-primary d-grid w-100">Create your Tanda account</a>
              <!-- <button class="btn btn-primary d-grid w-100" type="submit">Create your Tanda account</button> -->
            </div>
          </form>
          <p class="text-left">
            <span class="small text-muted">By confirming your email, you agree to see <a href="" class="text-dark">Term of services </a> and that you have read and understood of our <a href="" class="text-dark">Privacy policy</a>. </span>
          </p>
          <!-- /Register -->
        </div>
      </div>
    </div>
    <div class=" col-md-3 text-right ">
      <label class="mt-md-5 small text-dark"> Already have an account?</label>
      <a href="{{ route('login.get') }}" class="btn btn-sm btn-outline-warning">Login</a>
    </div>
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