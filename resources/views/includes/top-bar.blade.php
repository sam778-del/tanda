@php
    $defaultImage = 'avatar/avatar.png';
    $avatar = Auth::user()->photo !== NULL ? asset(Storage::url(Auth::user()->photo)) : asset('avatar/avatar.png');
@endphp

<!-- Page Header Start-->
<div class="page-header">
    <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper"><a href="{{ url('/dashboard') }}"><img class="img-fluid" src="{{ asset(Storage::url('logo/logo.jpeg')) }}" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
        </div>
        <div class="left-header col horizontal-wrapper ps-0">
            <ul class="horizontal-menu">
            </ul>
        </div>
        <div class="nav-right col-8 pull-right right-header p-0">
            <ul class="nav-menus">
                <li>
                    <div class="mode"><i class="{{ Auth::user()->mode === 1 ? 'fa fa-lightbulb-o' : 'fa fa-moon-o' }}"></i></div>
                </li>
                <li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                <li class="profile-nav onhover-dropdown p-0 me-0">
                    <div class="media profile-media"><img class="b-r-10" height="40" src="{{ $avatar }}" alt="">
                        <div class="media-body"><span>{{ Auth::user()->name }}</span>
                            <p class="mb-0 font-roboto">Admin <i class="middle fa fa-angle-down"></i></p>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        @can('Edit Profile')
                            <li>
                                <a href="{{ route('profile.update') }}">
                                    <i data-feather="user"></i>
                                    <span>Account </span>
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i data-feather="log-in"> </i>
                                <span>Log Out</span>
                            </a>
                            {!! Form::open(['method' => 'POST', 'id' => 'logout-form', 'route' => ['logout'], 'style' => 'display:none']) !!}
                            {!! Form::close() !!}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">
                <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
                <div class="ProfileCard-details">
                    <div class="ProfileCard-realName">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </script>
        <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
    </div>
</div>
<!-- Page Header Ends
