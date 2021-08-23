$(document).on('click', '.login-as-ambassador', function () {
    console.log("Getting distid -> "+jQuery(this).data( "distid" ));
    $.ajax({
        type: "GET",
        "url": '/auth-token-ambassador/' +jQuery(this).data( "distid" ),
        success: function (data) {
            
            if (data.error == 1) {
                errMsg(data.msg);

                return;
            }
            
            window.open(data.ambassador_url);
            // okMsg(data.msg);
        },
        error: function (data) {
            console.log('An unexpected error occured.');
            errMsg('An unexpected error occured.');
        }
    });
});

$('.datepicker').datepicker({  
    format: 'yyyy-mm-dd'
});  