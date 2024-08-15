jQuery(document).ready(function($) {
    $('.wpweb-activation_section #woo_slg_button').on('click',function(e) {
        e.preventDefault();
                
        var licenseAction = $(this).val();
        var licenseKey = $('.wpweb-activation_section .woo_slg_activation_code').val();
        var email = $('.wpweb-activation_section .woo_slg_email_address').val();

        if( licenseKey.length < 2 ) {
            Swal.fire(
                'Error',
                'Please enter license key',
                'error',                       
              );
              return;
        }
        if( ! licenseKey && ! email ) {
            Swal.fire(
                'Error',
                'Please enter license key and email',
                'error',                       
              );
              return;
        }
        if( ! wpwebIsEmail(email) ) {
            Swal.fire(
                'Error',
                'Please enter valid email',
                'error',                       
              );
              return;
        }
        $('#loader').css('display', 'flex');
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'woo_slg_activate_license',
                license_action: licenseAction,
                license_key: licenseKey,
                email: email
            },
            success: function(response) {
                // Handle the response data here
                $('.wpweb-activation_section #loader').hide();
                if(response.status==true){
                    if(licenseAction=='Activate License'){
                        $('.wpweb-activation_section #woo_slg_button').attr('value', 'Deactivate License');
                    } else {
                        $('.wpweb-activation_section #woo_slg_button').attr('value', 'Activate License');
                    }
                    Swal.fire(
                        'Success',
                        response.msg,
                        'success',                       
                      ).then(() => {
                        if(licenseAction=='Activate License'){
                            window.location.href = 'admin.php?page=woo-social-login';                        
                        } else {
                            window.location.reload();
                        }
                      });
                } else {
                    Swal.fire(
                        'Error',
                        response.msg,
                        'error',
                    );
                }
                
            },
        });
    });
});

function wpwebIsEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
  