@extends('layouts.app')

@push('stylesheets')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
    <!-- Plugins css Ends-->
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
            <h3>Send Notification</h3>
            </div>
            <div class="col-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active"> Notification</li>
            </ol>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-8">
        <div class="card">
          <div class="card-body">
            <form class="needs-validation">
                <div class="mb-3">
                    <div class="col-form-label">Select Customer</div>
                    <select name ="customer[]" class="form-check-input js-example-placeholder-multiple col-sm-12" multiple="multiple">
                        @foreach ($customer as $cust)
                            <option value="{{ $cust->id }}">{{ $cust->first_name.' '.$cust->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                      <div class="checkbox p-0">
                        <input class="form-check-input" id="invalidCheck" name="all" type="checkbox" value="off">
                        <label class="form-check-label" for="invalidCheck">Send to All Customer</label>
                      </div>
                    </div>
                  </div>
                <div class="mb-3">
                    <div class="col-form-label">Enter Title</div>
                    {!! Form::text('title', null, ["class" => 'col-sm-12 form-control', 'placeholder' => 'Enter Title']) !!}
                </div>
                <div class="mb-3">
                    <p>
                        <strong>Dynamic Tag : </strong>
                        <a data-value=" {first_name} " class="btn btn-dark-gradien btn-xs btn_tag ">{first_name}</a>
                        <a data-value=" {last_name} " class="btn btn-dark-gradien btn-xs btn_tag">{last_name}</a>
                        <a data-value=" {email} " class="btn btn-dark-gradien btn-xs btn_tag">{email}</a>
                    </p>
                </div>
                <div class="mb-3">
                    <div class="col-form-label">Enter Message</div>
                    {!! Form::textarea('description', null, ["class" => "col-sm-12 form-control", "id" => "message", "rows" => 6]) !!}
                </div>
                <button class="btn btn-primary" type="submit">
                    Send Message
                </button>
              </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
		$('.btn_tag').on('click', function() {
			var $txt = $("#message");
	     	var caretPos = $txt[0].selectionStart;
	        var textAreaTxt = $txt.val();
	        var txtToAdd = $(this).data("value");
	        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		});

        $('input[type="checkbox"]').change(function() {
            if ($(this).is(':checked')) {
                $(this).val('on');
                $(".js-example-placeholder-multiple").prop("disabled", true);
            }else{
                $(".js-example-placeholder-multiple").prop("disabled", false);
                $(this).val('off');
            }
        });

        $('button[type="submit"]').on('click', function(ele) {
            ele.preventDefault();
            $('button[type="submit"]').prop("disabled", true);
            var customers = $('select[name="customer[]"]').val();
            var check_data = $('input[name="all"]').val();
            var title  = $('input[name="title"').val();
            var description = $('textarea[name="description"]').val();
            $.ajax({
                "type": "POST",
                "url": "{{ route('notify.send') }}",
                "data": {
                    "_token": "{!! csrf_token() !!}",
                    "title": title,
                    "customer":customers,
                    "check": check_data,
                    "description": description
                },
                success: function(res) {
                    console.log(res);
                    if(res.error){
                        $.notify(`<i class="fa fa-bell-o"></i><strong>${res.message}</strong>`, {
                            type: 'theme',
                            allow_dismiss: true,
                            delay: 5000,
                            showProgressbar: true,
                            timer: 300,
                            animate:{
                                enter:'animated fadeInDown',
                                exit:'animated fadeOutUp'
                            }
                        });
                        $('button[type="submit"]').prop("disabled", false);
                    }else{
                        $.notify(`<i class="fa fa-bell-o"></i><strong>${res.message}</strong>`, {
                            type: 'theme',
                            allow_dismiss: true,
                            delay: 5000,
                            showProgressbar: true,
                            timer: 300,
                            animate:{
                                enter:'animated fadeInDown',
                                exit:'animated fadeOutUp'
                            }
                        });
                        $('button[type="submit"]').prop("disabled", false);
                    }
                }
            });
        });
    </script>
    <!-- Plugins JS Ends-->
@endpush
