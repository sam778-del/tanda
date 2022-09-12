<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- feather icon js-->
<script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<!-- scrollbar js-->
<script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
<!-- Sidebar jquery-->
<script src="{{ asset('assets/js/config.js') }}"></script>

<!-- Plugins JS start-->
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
<script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
@stack('scripts')



<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{ asset('assets/js/script.js') }}"></script>
@if(Session::has('success'))
<script>
    $.notify(`<i class="fa fa-bell-o"></i><strong>{{ session('success') }}</strong>`, {
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
</script>
@endif

@if(Session::has('error'))
<script>
    $.notify(`<i class="fa fa-bell-o"></i><strong>{{ session('error') }}</strong>`, {
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
</script>
@endif

<script type="text/javascript">
    "use strict";
    $(document).ready(function () {
        const BASE_URL = "{{ config('app.url') }}";

        $('body').on('focus',".datepicker", function(){
            $(this).datepicker({
                format:"yyyy-mm-dd",
                clearBtn:!0,
                autoclose:!0,
            });
        });

        $(document).on('click', 'div[class="mode"]', function() {
            $.ajax({
                url: '{{ route('theme.update') }}',
                type: 'POST',
                data:{
                    "_token": '{{ csrf_token() }}',
                },
                success: function(response){
                    $.notify(`<i class="fa fa-bell-o"></i><strong>${response.message}</strong>`, {
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
                }
            })
        });

        // Dynamic ajax endpoint for deleting data
        $(document).on('click', '.deleteRecord', function () {
            var url = $(this).data("url");
            var returnUrl = $(this).data('return_url');
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    "_token": token,
                },
                success: function (response) {
                    $.notify(`<i class="fa fa-bell-o"></i><strong>${response.message}</strong>`, {
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

                    setTimeout(() => {
                        window.location.href = returnUrl;
                    }, 5000)
                }
            });

        });

    });
</script>
