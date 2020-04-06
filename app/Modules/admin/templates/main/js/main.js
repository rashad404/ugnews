
$(document).ready(function(){
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