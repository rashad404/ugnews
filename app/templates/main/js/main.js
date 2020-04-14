

$(document).ready(function() {

//Umodal
    $(".umodal_toggle").click(function() {
        $(".umodal").show();
        $(".all_site").show();
        $("body").css({
            overflow: "hidden",
            position: "relative",
            height: "100%"
        });
        $("html").css({
            overflow: "hidden",
            position: "relative",
            height: "100%"
        });
        // $("#redirect_url").html($(this).attr('redirect_url'));
        $("#redirect_url_register").val($(this).attr('redirect_url'));
        $("#redirect_url_login").val($(this).attr('redirect_url'));
    });


    $(".umodal_close").click(function() {
        $(".umodal").hide();
        $(".all_site").hide();
        $("body").css({
            overflow: "auto",
            position: "none",
            height: "auto"
        });
        $("html").css({
            overflow: "auto",
            position: "none",
            height: "auto"
        });
    });

    $("#login_button").click(function() {
        $("#register_tab").hide();
        $("#login_tab").show();
        $("#umodal_title").html("Login");
    });
    $("#register_button").click(function() {
        $("#login_tab").hide();
        $("#register_tab").show();
        $("#umodal_title").html("Register");
    });


    // Subscribe
    $("#subscribe_button").click(function() {
        var channelId = $(this).attr('channel_id');
        if($("#subscribe_button").hasClass('umodal_toggle')){
            return;
        }
        if($("#subscribe_button").hasClass('subscribed')){

            $.ajax({
                url: "ajax/un_subscribe/" + channelId,
                type: "POST",
                data: "channel=" + channelId,
                success: function (response) {
                    console.log(response);

                    $("#subscribe_button").removeClass('subscribed');
                    $("#subscribe_button i").addClass('fa-bell');
                    $("#subscribe_button i").removeClass('fa-bell-slash');
                    $("#subscribe_button span").text(response);

                },
            });
        }else{

            $.ajax({
                url: "ajax/subscribe/" + channelId,
                type: "POST",
                data: "channel=" + channelId,
                success: function (response) {
                    console.log(response);

                    $("#subscribe_button").addClass('subscribed');

                    $("#subscribe_button i").removeClass('fa-bell');
                    $("#subscribe_button i").addClass('fa-bell-slash');
                    $("#subscribe_button span").text(response);

                },
            });
        }
    });



    // Like
    $("#like_button").click(function() {
        var newsId = $(this).attr('news_id');
        if($("#like_button").hasClass('umodal_toggle')){
            return;
        }
        if($("#like_button").hasClass('liked')){
            // alert('REMOVE LIKE');
            $.ajax({
                url: "ajax/remove_like/" + newsId,
                type: "POST",
                data: "news_id=" + newsId,
                success: function (response) {
                    console.log(response);

                    $("#like_button").removeClass('liked');
                    $("#dislike_button").removeClass('disliked');

                    // $("#like_button i").css('color', '#787878');


                    $("#like_button span").text(response);

                },
            });
        }else{
            // alert('LIKE');
            $.ajax({
                url: "ajax/like/" + newsId,
                type: "POST",
                data: "news_id=" + newsId,
                success: function (response) {
                    console.log(response);

                    $("#like_button").addClass('liked');
                    $("#dislike_button").removeClass('disliked');

                    // $("#like_button i").css('color', '#00B600');
                    // $("#dislike_button i").css('color', '#787878');
                    $("#like_button span").text(response);

                },
            });
        }
    });




    // Dislike
    $("#dislike_button").click(function() {
        var newsId = $(this).attr('news_id');
        if($("#dislike_button").hasClass('umodal_toggle')){
            return;
        }
        if($("#dislike_button").hasClass('disliked')){
            // alert('REMOVE DISLIKE');
            $.ajax({
                url: "ajax/remove_dislike/" + newsId,
                type: "POST",
                data: "news_id=" + newsId,
                success: function (response) {
                    console.log(response);

                    $("#dislike_button").removeClass('disliked');
                    $("#like_button").removeClass('liked');

                    // $("#dislike_button i").css('color', '#787878');
                    $("#dislike_button span").text(response);

                },
            });
        }else{
            // alert('DISLIKE');
            $.ajax({
                url: "ajax/dislike/" + newsId,
                type: "POST",
                data: "news_id=" + newsId,
                success: function (response) {
                    console.log(response);

                    $("#dislike_button").addClass('disliked');
                    $("#like_button").removeClass('liked');

                    // $("#dislike_button i").css('color', 'red');
                    // $("#like_button i").css('color', '#787878');
                    $("#dislike_button span").text(response);

                },
            });
        }
    });




// Header search
    $('#header_search_input').keyup(function () {
        var inputVal = $('#header_search_input').val();
        // alert(inputVal);

        if(inputVal.length>=1) {
            $(".all_site_no_bg").show();
            $("#headerSearchDropDown").show();
            $.ajax({
                url: "ajax/search/" + inputVal,
                type: "POST",
                data: "text=",
                success: function (response) {
                    console.log(response);
                    $("#headerSearchDropDown").html(response);
                },
            });
        }else{
            $("#locationDropDown").hide();
        }
    });


// Location Search input Dropdown CLICK
// Locates in Ajaxmodel.php locationSearchList method


    //Session flash
    $('div.flash_notification:empty').css('display','none');
    $("button#flash_close").click(function() {
        $("div.flash_notification").hide();
        $(".all_site").hide();
    });

    if ($("div.flash_notification").html().length > 0){
        $(".all_site").css('display','block');
    };



    $(".mobile_menu_icon").click(function() {
        $(".mobile_menu_icon").show();
        $(".all_site").show();
        $(".mobile_menu").show("fast");
        $("body").css({
            overflow: "hidden",
            position: "relative",
            height: "100%"
        });
        $("html").css({
            overflow: "hidden",
            position: "relative",
            height: "100%"
        });
    });

    $(".all_site").click(function() {
        $(".all_site").hide();
        $(".umodal").hide();
        $("#headerSearchDropDown").hide();
        $("div.flash_notification").hide();
        $(".mobile_menu").hide("fast");
        $("body").css({
            overflow: "auto",
            position: "none",
            height: "auto"
        });
        $("html").css({
            overflow: "auto",
            position: "none",
            height: "auto"
        });
    });



    $(".all_site_no_bg").click(function() {
        $(".all_site_no_bg").hide();
        $(".umodal").hide();
        $("#headerSearchDropDown").hide();
        $("div.flash_notification").hide();
        $(".mobile_menu").hide("fast");
        $("body").css({
            overflow: "auto",
            position: "none",
            height: "auto"
        });
        $("html").css({
            overflow: "auto",
            position: "none",
            height: "auto"
        });
    });

    $(".sub_menu_toggle").click(function() {
        var this_id = $(this).attr("data-id");
        $("#toggle-ul-"+this_id).toggle();
    })




    //Add to cart INNER PAGE
    $('#add_to_card_inner').on('click', function () {
        var cart = $('.header_cart_icon');
        var imgtodrag = $("#product_inner_main_img");
        if (imgtodrag) {
            var imgclone = imgtodrag.clone()
                .offset({
                    top: imgtodrag.offset().top,
                    left: imgtodrag.offset().left
                })
                .css({
                    'opacity': '0.5',
                    'position': 'absolute',
                    'height': '150px',
                    'width': '150px',
                    'z-index': '100'
                })
                .appendTo($('body'))
                .animate({
                    'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': 75,
                    'height': 75
                }, 1000)
            ;
            imgclone.animate({
                'width': 0,
                'height': 0
            }, function () {
                $(this).detach()
            });
        }

        var this_id = $(this).attr("data-id");
        var quantity = parseInt($("#inner_select_quantity option:selected"). val());
        $.ajax({url: "/cart/add/"+this_id+"/"+quantity, success: function(result){
                if(result==1){
                    var header_cart_count = parseInt($("#header_cart_count").text())+quantity;
                    $("#header_cart_count").text(header_cart_count);
                }
            }});
    });

    //Add to cart
    $('.card_button').on('click', function () {
        var cart = $('.header_cart_icon');
        var imgtodrag = $(this).parent().parent().parent(".product_card").find("img").eq(0);
        if (imgtodrag) {
            var imgclone = imgtodrag.clone()
                .offset({
                    top: imgtodrag.offset().top,
                    left: imgtodrag.offset().left
                })
                .css({
                    'opacity': '0.5',
                    'position': 'absolute',
                    'margin-top': '200px',
                    'height': '250px',
                    'width': '250px',
                    'z-index': '100'
                })
                .appendTo($('body'))
                .animate({
                    'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': 75,
                    'height': 75
                }, 1000)
            ;
            imgclone.animate({
                'width': 0,
                'height': 0
            }, function () {
                $(this).detach()
            });
        }

        var this_id = $(this).attr("data-id");
        $.ajax({url: "/cart/add/"+this_id+"/1", success: function(result){
            if(result==1){
                var header_cart_count = parseInt($("#header_cart_count").text())+1;
                $("#header_cart_count").text(header_cart_count);
            }
        }});

    });



    //Shopping Cart CHANGE QUANTITY

    $("input.cart_quantity").change(function () {

        var this_id = $(this).attr("data-id");
        var cart_subtotal = $('#cart_subtotal_'+this_id);
        var cart_total = $('.cart_total');
        var header_cart_count = $('#header_cart_count');
        var price = $('#cart_price_'+this_id).text();

        var quantity = $(this).val();
        if(quantity<1){
            quantity=1;
            $(this).val(1);
        }

        var new_subtotal = (quantity*price).toFixed(2);
        cart_subtotal.text(new_subtotal);

        var total_price = 0;
        var total_quantity = 0;
        $('.subtotal').each(function() {
            total_price += Number($(this).text());
        });
        $('.cart_quantity').each(function() {
            total_quantity += Number($(this).val());
        });

        total_price = total_price.toFixed(2);

        cart_total.text(total_price);
        header_cart_count.text(total_quantity);

        $.ajax({url: "/cart/update/"+this_id+"/"+quantity, success: function(result){
            if(result==1){
                // var header_cart_count = parseInt($("#header_cart_count").text())+1;
                // $("#header_cart_count").text(header_cart_count);
            }
        }});

    });

    //Delete from shopping cart
    $("button.cart_delete").click(function () {
        var this_id = $(this).attr("data-id");
        $.ajax({url: "/cart/delete/"+this_id, success: function(result){
            if(result==1){
                $("tr.cart_item_"+this_id).remove();

                var cart_total = $('.cart_total');
                var header_cart_count = $('#header_cart_count');
                var total_price = 0;
                var total_quantity = 0;
                $('.subtotal').each(function() {
                    total_price += Number($(this).text());
                });
                $('.cart_quantity').each(function() {
                    total_quantity += Number($(this).val());
                });

                if(total_quantity==0){
                    $("h4.empty_cart").removeClass('hide');
                    $("table#cart_table").addClass('hide');
                    $("table#card_table_footer").addClass('hide');
                }

                total_price = total_price.toFixed(2);

                cart_total.text(total_price);
                header_cart_count.text(total_quantity);
            }
        }});

    });

    $(".payment_type").click(function () {
        if ($(this).is(':checked')) {
           value = $(this).val();
           $("#cash_content").addClass('hidden');
           $("#card_content").addClass('hidden');
           $("#paypal_content").addClass('hidden');
           $("#"+value+"_content").removeClass('hidden');
        }
    });

    $("#mobile_filter").click(function () {
           $("#left-sidebar").toggle();
    });
    $(".mobile_filter_close").click(function () {
           $("#left-sidebar").toggle();
    });

    //Forum
    $(".forum_sidebar_toggle").click(function() {
        $(".forum_sidebar").toggle();
    })
    $(".forum_sidebar_close").click(function () {
        $(".forum_sidebar").toggle();
    });

});