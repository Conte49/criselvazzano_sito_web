(function($) {
    
    $(document).ready(function(){

        $(".disablelogin-dismiss").click( function(){

            $.ajax({
                url: ajaxurl,
                type: "POST",
                data: "action=set_disable_login_nag_transient",
            });
        
        });

    });
    
})(jQuery);