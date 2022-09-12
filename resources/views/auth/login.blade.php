@extends('layouts.app')

@section('content')
<!-- login page start-->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-7"><img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/2.jpg') }}"
                                   alt="looginpage"></div>
        <div class="col-xl-5 p-0">
            <div class="login-card">
                <div>
                    <div>
                        <h2>Tanda Finance</h2>
                    </div>
                    <div class="login-main">
                        @include('includes.alert')
                        <h4>Sign in to account</h4>
                        <p>Enter your email & password to login</p>
                        <form class="theme-form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label">Email Address</label>
                                <input class="form-control" name="email" type="email" required=""
                                       placeholder="Test@gmail.com">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Password</label>
                                <div class="form-input position-relative">
                                    <input class="form-control" type="password"
                                           name="password" required=""
                                           placeholder="*********">
                                    <div class="show-hide"><span
                                            class="show">                         </span></div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="checkbox p-0">
                                    <input id="checkbox1" type="checkbox">
                                    <label class="text-muted" for="checkbox1">Remember
                                        password</label>
                                </div>
                                <button class="btn btn-primary btn-block w-100" type="submit">Sign
                                    in
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
