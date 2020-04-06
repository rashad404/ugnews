$(document).ready(function () {

    /***************************************** Rotate image ***********************************************/
    $(document).delegate('.rotate', 'click', function ()    // rotate
    {

        var e = $(this);
        var rotate_image_url = $('.rotate_img_url').data('rotateimageurl');
        var thumbImageId = e.parent('li').data('photoid');
        var wait = e.parent('li').hasClass('wait');
        if (wait == false) {
            var action = e.hasClass('turn_left');
            if (action == true) action = 'left'; else action = 'right';
            e.parent('li').addClass('wait');
            e.parent('li').children('div').children('div').children('img').css('opacity', '0.3');

            // var thumbImage = e.parent('li').children('div').children('div').children('img').attr('src');
            // thumbImage = thumbImage.split('Web/uploads/photos/elan/thumbs/');
            // thumbImage = thumbImage[1];
            var temporaryImage = e.hasClass('turn_new');
            if (temporaryImage == true) temporaryImage = 1; else temporaryImage = 0;
            // $.post("images/rotate", {
            $.post(rotate_image_url, {
                thumbImageId: thumbImageId,
                temporaryImage: temporaryImage,
                action: action
            }, function (data) {
                console.log(data);
                if (data == 'rotated') {
                    var hidden_rotate = parseInt(e.parent('li').children('.delete_photo').attr('degrees'));
                    if (action == 'left') hidden_rotate -= 90; else hidden_rotate += 90;
                    e.parent('li').children('div').children('div').children('img').rotate({
                        duration: 1000,
                        animateTo: hidden_rotate
                    });
                    if (hidden_rotate % 360 == 0) hidden_rotate = 0;
                    e.parent('li').children('.delete_photo').attr('degrees', hidden_rotate);
                }
                e.parent('li').removeClass('wait');
                e.parent('li').children('div').children('div').children('img').css('opacity', '1');
            });
        }
    });

    /***************************************** Delete image ***************************************/
    $(document).delegate('.delete_photo', 'click', function () {
        var delete_image_url = $('.delete_img_url').data('delimageurl');

        var e = $(this);
        var thumbImageId = e.parent('li').data('photoid');

        //var file_url = $('#add_elan_form').data('imageurl');
        //var thumbImage = e.parent('li').children('div').children('div').children('img').attr('src');
        //thumbImage = thumbImage.split(file_url+'thumbs/');
        //thumbImage = thumbImage[1];
        var temporaryImage = e.hasClass('delete_photo_new');
        if (temporaryImage == true) temporaryImage = 1; else temporaryImage = 0;
        // $.post(delete_image_url, {thumbImage: thumbImage, temporaryImage: temporaryImage}, function (data) {
        $.post(delete_image_url, {thumbImageId: thumbImageId}, function (data) {
            console.log(data);
            e.parent('li').remove();

        });
    });
});
/******************************************* ADDING PHOTOS FOR ELAN **********************************************/

$(function () {

    $('.add_photo').click(function (e) {
        $('#files').trigger('click');

    });

    $("#files").change(function (e) {
        $("#submit_form").trigger('click');
    });


    $('#add_elan_form').submit(function (e) {

        var $this = $(this);
        e.preventDefault();

        //console.log($photo_text);
        var last_bar = parseInt($("#last_bar").val());
        last_bar++;
        $("#last_bar").val(last_bar);
        e.last_bar = last_bar;
        $('#add_elan_form').ajaxSubmit({
            url: $this.attr('action'),
            type: 'post',
            beforeSend: function () {

                var files_val = $("#files").val();

                if (files_val != '') {
                    $(".progress").removeClass('hide');
                    var append = '';
                    var files = $("#files").get(0).files.length;
                    var image_count = $(".photo_list li").length;
                    var img_file_count = files + image_count;


                    for (var i = 1; i <= files; i++) {

                        append += '<li data-photoid="0">';
                        append += '<div class="li_div">';
                        append += '<div class="li_div2"><img class="uploading_image" src="app/templates/main/icons/uploading_image.gif" alt="" /></div>';
                        append += '<div class="progress"><div class="bar bar' + e.last_bar + '"></div></div>';
                        append += '</div>';
                        append += '<a href="javascript:void(0);" degrees="0" class="delete_photo delete_photo_new" style="display: none"></a>';
                        append += '<a href="javascript:void(0);" class="turn_left turn_new rotate" style="display: none"></a>';
                        append += '<a href="javascript:void(0);" class="turn_right turn_new rotate" style="display: none"></a>';
                        append += '<br>';
                        append += '</li>';
                    }
                    $(".photo_list").append(append);
                }
            },
            uploadProgress: function (event, position, total, percentComplete) {
                var pVel = percentComplete + '%';
                $('.bar' + e.last_bar).width(pVel);

            },
            success: function (data) {
                console.log(data);
                var files_val = $("#files").val();
                if (files_val != '') {
                    //var newImagesThumb = data.split('~~~');
                    var newImagesThumb = eval(data);
                    var src = 'app/templates/main/icons/uploading_image.gif';
                    var i = 0;
                    $(".bar" + e.last_bar).each(function () {
                        var thiis = $(this).parents('div.li_div').children('div.li_div2').children('img');
                        if (newImagesThumb[i] != null) {
                            var new_src = $this.data('imageurl') + 'thumbs/' + newImagesThumb[i].image_name;
                            thiis.attr('src', new_src);
                            thiis.parents('li').attr('data-photoid', newImagesThumb[i].id);
                            thiis.parents('.li_div').children('.progress').fadeOut('slow');
                            thiis.parents('li').children('a').fadeIn('slow');
                            thiis.parents('li').find('.main_image').val(newImagesThumb[i].image_name);
                        }
                        i++;
                    });
                }

                var image_count = $(".photo_list li").length;

                if (image_count >= 0 && image_count <= 12) {
                    $('.add_photo').show();

                } else {
                    $('.add_photo').hide();

                }
            },
            error: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log(data);
            }
        });


    });

});

/* ################################################################################################ */