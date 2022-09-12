@extends('frontend.layouts.app')

@push('stylesheets') 
<style>
 hr{
    font-family: 'Inter';
    font-style: normal;
    font-weight: 600;
    font-size: 24px;
    line-height: 29px;
    /* identical to box height */
    color: #19224C;
 }

 .acting{
    color: #F89673;
 }

  .employee-text{
    font-weight: 400;
    font-size: 16px;
    line-height: 20px;
    color: #19224C;
    margin-top: 25px;
  }

  .onboard-text{
    font-weight: 400;
    font-size: 14px;
    line-height: 18px;
    /* identical to box height */
    color: #A3A7B7;
  }

  .button-employee{
    width: 151px;
    height: 49px;
    border-color: #FFC7B4;
    background: linear-gradient(180deg, #FFC7B4 0%, #F89673 100%);
    border-radius: 10px;
    color: #FFFFFF;
    font-weight: 500;
    font-size: 20px;
    line-height: 25px;
    margin-bottom: 105px;
    margin-top: 25px;
  }

  .button-employee-s{
    width: 151px;
    height: 49px;
    border-color: #FFC7B4;
    background: linear-gradient(180deg, #FFC7B4 0%, #F89673 100%);
    border-radius: 10px;
    color: #FFFFFF;
    font-weight: 500;
    font-size: 20px;
    line-height: 25px;
    margin-left: 155px;
  }

  @media only screen and (max-width: 600px) {
    .button-employee-s{
        width: 100px;
        height: 49px;
        border-color: #FFC7B4;
        background: linear-gradient(180deg, #FFC7B4 0%, #F89673 100%);
        border-radius: 10px;
        color: #FFFFFF;
        font-weight: 500;
        font-size: 10px;
        line-height: 15px;
        margin-left: 0px;
    }

    .card-header{
        height: 180px;
    }
  }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row col-12">
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header" style="height: 180px">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <h4>Employees</h4>
                </div>
                <hr>
                <div class="row">
                    <div class="col-8">
                        <div class="nav-align-top mb-4">
                            <ul class="nav nav-pills mb-3" role="tablist"">
                                <li class="nav-item">
                                    <a href="{{ url('employee?active=' . true . '') }}" class="nav-link {{ isset($_GET['active']) && $_GET['active'] == 1 && Request::segment(1) == 'employee' ? 'active' : '' }}" style="font-size: 16px">
                                        Active Employees
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('employee?terminated=' . true . '') }}" class="nav-link {{ isset($_GET['terminated']) && $_GET['terminated'] == 1 && Request::segment(1) == 'employee' ? 'active' : '' }}" style="font-size: 16px">
                                        Terminated
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <button class="button-employee-s"><i class="fa fa-plus-circle"></i> Onboard</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center align-items-center" align="center">
                    <div style="margin-top: 80px">
                        <img src="{{ asset('frontend/img/shimmer.png') }}" alt="step 1">
                    </div>
                    <div style="margin-top: 20px">
                        <img src="{{ asset('frontend/img/shimmer.png') }}" alt="step 1">
                    </div>
                    <p class="employee-text">No Employee Added yet</p>
                    <p class="onboard-text">Onboard your employees instantly by clicking on the Onboard button</p>
                    <p><button class="button-employee"><i class="fa fa-plus-circle"></i> Onboard</button></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush