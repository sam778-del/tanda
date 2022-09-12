
<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="./"
    data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>
        {{ config('app.name', 'Tanda') }}
        @if(trim($__env->yieldContent('page-title')))
            | @yield('page-title')
        @endif
    </title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon/favicon.ico') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('frontend/fonts/boxicons.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('frontend/css/demo.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('frontend/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('frontend/js/config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('frontend/css/toastr.min.css') }}" />
    @stack('stylesheet')
</head>

<body>
    <!-- Content -->
    <div class="container-fluid">
        @yield('container')
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('frontend/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('frontend/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('frontend/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('frontend/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('frontend//vendor/js/menu.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
    @stack('scripts')
    @if(Session::has('success'))
        <script>
            show_toastr("{{__('Success') }}", "{!! session('success') !!}", 'success');
        </script>
    @endif
    @if(Session::has('error'))
        <script>
            show_toastr("{{__('Error') }}", "{!! session('error') !!}", 'error');
        </script>
    @endif
</body>
</html>