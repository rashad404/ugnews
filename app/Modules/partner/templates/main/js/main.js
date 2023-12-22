
$(document).ready(function(){

    setTimeout(function() {
        $('.alert').fadeOut('fast');
        $('.all_site').fadeOut('fast');
    }, 1000); // <-- time in milliseconds


    $('input[type=checkbox][name=urlhave]').change(function() {

        var $check = $(this),
            $div = $('.rekurl');

        if ($check.prop('checked')) {

            $div.removeClass('hidden');

        } else {

            $div.addClass('hidden');

        }

    });
    
});