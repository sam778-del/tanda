<aside id="layout-menu" class="  layout-menu menu-vertical mt-md-4 menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="index.php" class="app-brand-link">
        <span class="app-brand-logo demo">
          <img width="50" viewBox="0 0 25 42" src="{{ asset('frontend\img\logo\logo.png') }}" />
        </span>
        <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ config('app.name') }}</span>
      </a>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-4">
      <!-- Dashboard 
      <li class="menu-item active"><a href="index.html" class="menu-link"><i class="menu-icon tf-icons bx bx-home-circle"></i><div data-i18n="Analytics">Dashboard</div></a></li>-->
      <li class="menu-item  {{ Request::segment(1) == 'overview' ? 'active' : '' }}">
        <a href="{{ route('overview.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Overview"> Overview</div>
          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20" style="margin-left: auto;">2</span>
        </a>
      </li>
      <li class="menu-item" {{ Request::segment(1) == 'employee' ? 'active' : '' }}>
        <a href="{{ url('employee?active=' . true . '') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user-circle"></i>
          <div data-i18n="Employees">Employees</div>
        </a>
      </li>
      <!-- Payrolls -->
      <li class="menu-item  ">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-money"></i>
          <div data-i18n="Payrolls">Payrolls</div>
        </a>
        <ul class="menu-sub  ">
          <li class="menu-item  ">
            <a href="runpayrolltab.php" class="menu-link">
              <div data-i18n="Run Payroll">Run Payroll</div>
            </a>
          </li>
          <li class="menu-item ">
            <a href="payscheduletab.php" class="menu-link">
              <div data-i18n="Pay Schedule">Pay Schedule</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item ">
        <a href="taxandcompliance.php" class="menu-link">
          <i class="menu-icon tf-icons bx bx-wallet"></i>
          <div data-i18n="Tax & Compliance">Tax & Compliance</div>
        </a>
      </li>
      <li class="menu-item ">
        <a href="setting.php" class="menu-link">
          <i class="menu-icon tf-icons bx bx-cog"></i>
          <div data-i18n="Settings">Settings</div>
        </a>
      </li>
      <li class="menu-item mt-auto">
        <a href="/issues" target="_blank" class="menu-link" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modelhelpandsupport">
          <i class="menu-icon tf-icons bx bx-question-mark "></i>
          <div data-i18n=">Help & Support">Help & Support</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="{{ route('employer.logout') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-exit"></i>
          <div data-i18n="Logout">Log Out</div>
        </a>
      </li>
    </ul>
  </aside>