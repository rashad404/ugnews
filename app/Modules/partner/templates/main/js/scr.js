$(function(){
    $(".offcanvas").on('click',function(){
        if($('.main-sidebar').width() < 230){
            setTimeout(function(){
                $('.footerbox').css({'left':'0px','border-top':'1px solid #eee','bottom':'10px'});
                $('.footertext1').css({'transform':'rotate(0deg)'});
                $('.footertext2').css({'display':'block'});
            },300);
            $('.mianlogocenter').css({'left':'35%'});
        } else {
                $('.footerbox').css({'left': '-18px', 'border-top': 'none', 'bottom': '15px'});
                $('.footertext1').css({'transform': 'rotate(-90deg)'});
                $('.footertext2').css({'display': 'none'});
                $('.mianlogocenter').css({'left':'44%'});
        }
    });


    $(".admin-switch").bootstrapSwitch();

    $(".admin-switch").on('switchChange.bootstrapSwitch', function (event, state) {

        var baseUrl = $("#baseUrl").val();
        var id = $(this).val();
        // alert(baseUrl+"/status/"+id);
        $.post(baseUrl+"/status/"+id,{},function(data){

        })
    });

    $('.emelbtn').on('mouseover', function(){
        $(this).next('div.tooltip').css({'background':'gray !important'});
    });

    $('.summernote').summernote({
        height: 102,
        toolbar:  [
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear', 'fontsize']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['height', ['height']],
            ['insert', ['link', 'picture', 'hr', 'video']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ]
    });

    $('.evez').on('click' ,function(){
        $('#file_1').click();
    });


});