<!doctype html>
<html lang="en">
@include('includes.header-meta')
@yield('styles')
<body @auth class="{{ Auth::user()->mode === 1 ? 'dark-only' : '' }}" @endauth>
<!-- loader starts-->
<div class="loader-wrapper">
    <div class="loader-index"><span></span></div>
    <svg>
        <defs></defs>
        <filter id="goo">
        <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
        <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"> </fecolormatrix>
        </filter>
    </svg>
    </div>
    <!-- loader ends-->
<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- Begin page -->
<div class="page-wrapper compact-wrapper" id="pageWrapper">

    <!-- Start Side bar -->
@if(!is_null(auth()->user()))
    @include('includes.top-bar')
    <!-- Page Body Start-->
    <div class="page-body-wrapper">
        @include('includes.sidebar')

        @include('includes.modals')
        <!-- Start Content -->
            <div class="page-body">
                @yield('content')
            </div>
            <!-- End Content-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 footer-copyright text-center">
                            <p class="mb-0">Copyright {{ date('Y') }} &copy; Tanda Finance </p>
                        </div>
                    </div>
                </div>
            </footer>
            @else
                @yield('content')
    </div>
@endif
@include('includes.footer')
@yield('script')
</body>
</html>
