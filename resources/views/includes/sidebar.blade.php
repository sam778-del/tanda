<!-- Page Sidebar Start-->
<div class="sidebar-wrapper">
    <div>
        <div class="logo-wrapper"><a href="{{ url('/dashboard') }}"><img class="for-light" height="40" src="{{ asset(Storage::url('logo/logo.jpeg')) }}" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
        </div>
        <div class="logo-icon-wrapper"><a href="{{ url('/dashboard') }}"><img class="" height="40" src="{{ asset(Storage::url('logo/logo.jpeg')) }}" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="{{ url('/dashboard') }}"><img class="" height="40" src="{{ asset(Storage::url('logo/logo.jpeg')) }}" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ url('/dashboard') }}">
                            <i data-feather="home"> </i><span>{{ __('Dashboard') }}</span>
                        </a>
                    </li>

                    @if(Auth::user()->can('Manage Customer'))
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="users"></i>
                                <span>{{ __('Customer Management') }}</span>
                            </a>
                            <ul class="sidebar-submenu">
                                @can('Manage Customer')
                                    <li><a href="{{ route('customer.index') }}">Customer List</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->can('Manage Global Setting'))
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="settings"></i>
                                <span>{{ __('Global Setting Page') }}</span>
                            </a>
                            <ul class="sidebar-submenu">
                                @can('Manage Email')
                                    <li><a href="{{ route('email.index') }}">Email Setting</a></li>
                                @endcan
                                @can('Manage Sms')
                                    <li><a href="{{ route('sms.index') }}">Sms Setting</a></li>
                                @endcan
                                @can('Manage Notification')
                                    <li><a href="{{ route('notify.index') }}">Notification Setting</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->can('Manage Savings Goal Management'))
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="sliders"></i>
                                <span>{{ __('Savings Goal') }}</span>
                            </a>
                            <ul class="sidebar-submenu">
                                @can('Manage Savings Goal Management')
                                    <li><a href="{{ route('saving.index') }}">Savings Goal Management</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->can('Manage Smart Rule Management'))
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="cloud"></i>
                                <span>{{ __('Smart Rule') }}</span>
                            </a>
                            <ul class="sidebar-submenu">
                                @can('Manage Smart Rule Management')
                                    <li><a href="{{ route('rule.category') }}">Category</a></li>
                                    <li><a href="{{ route('saving.index') }}">Smart Rule</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @can('Manage Saving Transaction')
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('saving_transaction.index') }}">
                                <i data-feather="briefcase"></i>
                                <span>{{ __('Saving Transaction') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('Manage User')
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('staff.index') }}">
                                <i data-feather="user-check"></i>
                                <span>{{ __('Staff Management') }}</span>
                            </a>
                        </li>
                    @endcan

                    @if(Auth::user()->can('Manage Bill Payment') || Auth::user()->can('Manage Bill Transaction'))
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="check-square"></i>
                                <span>{{ __('Bill Management') }}</span>
                            </a>
                            <ul class="sidebar-submenu">
                                @can('Manage Smart Rule Management')
                                    <li><a href="{{ route('bill.payment') }}">Bill Payment</a></li>
                                    <li><a href="{{ route('bill.transaction') }}">Bill Transaction</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    <!-- Roles & Permission-->
                    @if(Auth::user()->can('Manage Role') || Auth::user()->can('Manage Permission'))
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="lock"></i>
                                <span>{{ __('Roles & Permissions') }}</span>
                            </a>
                            <ul class="sidebar-submenu">
                                @can('Manage Role')
                                    <li><a href="{{ route('roles.index') }}">Roles</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
<!-- Page Sidebar Ends-->
