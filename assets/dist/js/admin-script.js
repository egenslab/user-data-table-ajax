(function($) {
    "use strict";
    /*--------Paginations Click Event-------*/
    $(document).on('click','.pagination-link ul li',function(){
       var page         = $(this).attr('p');
        var orderBy     = $('#orderBy').val();
        var role       = $('#userRole').val();

       $(this).addClass('selected');
       load_all_users(page,orderBy,role);
    });

    jQuery("#userRole").change(function(){
        var orderBy = jQuery('#orderBy').val();
        var adminRole = jQuery(this).val();
        load_all_users(1,orderBy,adminRole);
    });

    jQuery("#orderBy").change(function(){
        var orderBy = jQuery(this).val();
        var role = jQuery('#userRole').val();
        load_all_users(1,orderBy,role);
    });


    $(document).on('click','#orderByDesc',function(){
        var orderBy     ='desc';
        var role       = $('#userRole').val();
        load_all_users(1, orderBy, role);
    });

    $(document).on('click','#orderByAsc',function(){
        var orderBy     ='asc';
        var role       = $('#userRole').val();
        load_all_users(1,orderBy,role);
    });


    /*--------Loaded All User List-------*/
    function load_all_users(page,orderBy='',adminRole=''){
        var adminAjaxUrl = jQuery('#adminAjaxUrl').val();
        var data = {
            page: page,
            orderBy: orderBy,
            adminRole: adminRole,
            action: "pagination_user_list"
        };
        $.post(adminAjaxUrl, data, function(response) {
            if (response) {
                $('div#cardLoadedContent').html(response);
            }
        });
    }


})(jQuery);
