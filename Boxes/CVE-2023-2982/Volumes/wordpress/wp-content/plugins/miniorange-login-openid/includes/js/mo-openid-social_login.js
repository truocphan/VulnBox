jQuery(window).on('load',function () {
    function mo_openid_set_cookie(cname,cvalue){var d=new Date();d.setTime(d.getTime()+(3*60*1000));var expires="expires="+d.toUTCString();document.cookie=cname+"="+cvalue+";"+expires+";path=/"}
    function mo_openid_get_cookie(cname){var name=cname+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' '){c=c.substring(1)}
        if(c.indexOf(name)==0){return c.substring(name.length,c.length)}}
        return""}

    // If cookie is set, scroll to the position saved in the cookie.
    if ( (mo_openid_get_cookie("scroll")) !== 'null' ) {
        jQuery(document).scrollTop( mo_openid_get_cookie("scroll") );
        mo_openid_set_cookie("scroll",null);
    }

    // When a button is clicked...
    jQuery('.custom-login-button').on("click", function() {
        // Set a cookie that holds the scroll position.
        mo_openid_set_cookie("scroll",jQuery(document).scrollTop());
    });

    jQuery('.login-button').on("click", function() {
        // Set a cookie that holds the scroll position.
        mo_openid_set_cookie("scroll",jQuery(document).scrollTop());
    });


});

jQuery(document).ready(function () {
    //show mcrypt extension installation reason

    jQuery("#openid_sharing_shortcode_title").click(function () {
        jQuery("#openid_sharing_shortcode").slideToggle(400);
    });

});


if (window.location.hash && window.location.hash == '#_=_') {
    window.location.href = window.location.href.split('#_=_')[0];
}

