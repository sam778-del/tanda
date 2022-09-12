$(".swa-confirm").on("click", function(e) {
    e.preventDefault(); //to prevent submitting
    swal({
        title: $(this).attr('data-title'),
        text: $(this).attr('data-confirm'),
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            eval($(this).attr('data-confirm-yes'));
        } else {
            swal("No action made!", {icon: "info"});
        }
    })
    return true;
});
