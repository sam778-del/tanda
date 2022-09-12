var DropzoneExample = function () {
    var DropzoneDemos = function () {
        Dropzone.options.singleFileUpload = {
            maxFilesize:10,
            maxFiles: 1,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
               return time+file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            timeout: 50000,
            success: function(file, response)
            {
                $.notify(`<i class="fa fa-bell-o"></i><strong>Avatar Updated</strong>`, {
                    type: 'theme',
                    allow_dismiss: true,
                    delay: 1000,
                    showProgressbar: true,
                    timer: 300,
                    animate:{
                        enter:'animated fadeInDown',
                        exit:'animated fadeOutUp'
                    }
                });

                setTimeout(function() {
                    location.reload();
                }, 3000);
            },
            error: function(file, response)
            {
                $.notify(`<i class="fa fa-bell-o"></i><strong>${response}</strong>`, {
                    type: 'theme',
                    allow_dismiss: true,
                    delay: 1000,
                    showProgressbar: true,
                    timer: 300,
                    animate:{
                        enter:'animated fadeInDown',
                        exit:'animated fadeOutUp'
                    }
                });

                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
        };
    }
    return {
        init: function() {
            DropzoneDemos();
        }
    };
}();
DropzoneExample.init();
