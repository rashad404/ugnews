<?php
use Helpers\Assets;
use Helpers\Hooks;
use Core\Language;
use Models\LanguagesModel;
use \Models\MenusModel;
use Helpers\Session;
use Models\UserModel;
use Helpers\Url;
use Helpers\Cookie;
use Helpers\Security;

//Country settings
$_SETTINGS = [];
if(Cookie::has('set_region')===true){
    $_SETTINGS['region'] = Cookie::get('set_region');
}else{
    Cookie::set('set_region', DEFAULT_COUNTRY);
    $_SETTINGS['region'] = DEFAULT_COUNTRY;
}


if(Cookie::has('uniqueId')===false){
    Cookie::set('uniqueId', Security::generateHash());
}
$css_v = '?v='.UPDATE_VERSION;
$lng = new Language();
$lng->load('app');


$hooks = Hooks::get();

$userId = intval(Session::get("user_session_id"));

if($userId>0) {
    //Timeout for inactivity START
    if(Session::get("timestamp")>0 && time() - Session::get("timestamp") > LOGOUT_TIME) { //subtract new timestamp from the old one
        Session::destroy();
        header("Location: ".DIR); //redirect to index.php
        exit;
    } else {
        Session::set("timestamp",time());//set new timestamp
    }
    //Timeout for inactivity END

    $userModel = new UserModel();
    $userInfo = $userModel->getInfo($userId);
    if($userInfo['block']==1){
        echo $lng->get("Your account has been blocked");exit;
    }else{
        UserModel::updateOnline();
    }
}
$menusObject = new MenusModel();
$data['menus'] = $menusObject->getMenus();
$data['defLang'] = LanguagesModel::defaultLanguage("app");

$_PARTNER = [];


Cookie::set('partner_id', 1);
$_PARTNER['id'] = 1;



$_PARTNER = \Models\PartnerModel::getInfo($_PARTNER['id']);

?>

<!DOCTYPE html>
<html class="no_overflow">
<head lang="en">
    <base href="<?=DIR?>" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
    <meta name="author" content="<?=SITE_NAME?>">
    <meta name="keywords" content="<?=$data['keywords']?>">
    <meta name="description" content="<?=$data['description']?>">
    <meta name="copyright" content="<?=SITE_NAME?>" />

    <meta property="og:title" content="<?=$data['title']?>">
    <meta property="og:description" content="<?=$data['description']?>">
    <?= isset($data['meta_img'])?'<meta property="og:image" content="https://'.$_SERVER['HTTP_HOST'].'/'.Url::uploadPath().$data['meta_img'].'">':'';?>

    <meta property="og:url" content="https://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=Url::templatePath()?>img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=Url::templatePath()?>img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?=Url::templatePath()?>img/favicon/site.webmanifest">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <?php $hooks->run('meta'); ?>
    <?php
        if(in_array('summernote',$data['extra_css'])){
            $summernote_css = 'app/Components/summernote/summernote.css';
        }else{
            $summernote_css = '';
        }

        if(in_array('summernote',$data['extra_js'])){
            $summernote_js = 'app/Components/summernote/summernote.js';
        }else{
            $summernote_js = '';
        }

    Assets::css(array(
        Url::templatePath().'css/app.css'.$css_v,
        $summernote_css,
        Url::templatePath().'css/bootstrap.min.css',
        Url::templatePath().'assets/fontawesome-55/css/fontawesome.min.css',
        Url::templatePath().'assets/slick/css/slick.css'.$css_v,
        Url::templatePath().'assets/slick/css/slick-lightbox.css'.$css_v,
        Url::templatePath().'assets/slick/css/slick-theme.css'.$css_v,
        Url::templatePath().'assets/gallery/style.css',
        Url::templatePath().'css/bootstrap-tagsinput.css',
        '//fonts.googleapis.com/css?family=Oswald:200,300,400,500,600,700',
        Url::templatePath().'assets/owlcarousel/owl.carousel.min.css',
        Url::templatePath().'assets/owlcarousel/owl.theme.default.min.css',
        Url::templatePath().'assets/datepicker/bootstrap-datetimepicker.min.css',

    ));
    $hooks->run('css');
    ?>
    <title><?php echo $data['title']; ?></title>
</head>
<body class="no_overflow">

<?php
$jsArray = $jsContacts = [];
$jsArray = [
    Url::templatePath() . 'js/jquery.min.js'.$css_v,
    Url::templatePath() . 'js/popper.min.js',
    Url::templatePath() . 'js/bootstrap.min.js',
    Url::templatePath() . 'js/waypoints.min.js',
    Url::templatePath() . 'js/jquery.counterup.min.js',
    $summernote_js,
    Url::templatePath().'assets/slick/js/slick.min.js',
    Url::templatePath().'assets/slick/js/slick-lightbox.js',
    Url::templatePath().'assets/gallery/jquery.picEyes.js',
    Url::templatePath() . 'js/main.js'.$css_v,
    Url::templatePath() . 'js/bootstrap-tagsinput.min.js',
    Url::templatePath() . 'assets/owlcarousel/owl.carousel.js',
    Url::templatePath() . 'js/moment.min.js',
    Url::templatePath() . 'assets/datepicker/bootstrap-datetimepicker.min.js',

];

if($_SERVER['REQUEST_URI'] == DIR.'contacts'){
    $jsContacts = ['https://maps.googleapis.com/maps/api/js?key=AIzaSyAs-_XBiik7yqVJidUCqIwY3uzmsYIwug4'];
}

$jsAll = array_merge($jsArray, $jsContacts);
Assets::js($jsAll);

$hooks->run('js');

$hooks->run('footer');

?>

<script>
    $(document).ready(function() {
        <?php if(in_array('summernote',$data['extra_js'])){?>
        $('#summernote').summernote({
            placeholder: '<?=$lng->get("Type")?>...',
            tabsize: 2,
            height: 200});
        <?php }?>


        $('.slider-for').slick({
            slidesToShow: 10,
            slidesToScroll: 1,
            dots: false,
            centerMode: false,
            arrows:false,
            focusOnSelect: true,
            asNavFor: '.slider-nav'
        });
        $('.slider-nav').slickLightbox({
            src: 'src',
            itemSelector        : 'img',
            navigateByKeyboard  : true
        });
        $('.slider-nav').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false,
            centerMode: true,
            infinite: true,
            focusOnSelect: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 2,
                        dots: false
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ],
            asNavFor: '.slider-for'
        });
        $(function(){
            $('li.media').picEyes();
        });

    });
</script>


<?php $hooks->run('afterBody');

//if(isset($data['header_off']) && $data['header_off']) {
if(Session::get('header_off')==true) {
    eval($content);
}else{
    if ($userId > 0) {
        include_once 'inc/header.php';
        eval($content);
        include_once 'inc/footer.php';
    } else {
        include_once 'inc/header.php';
        eval($content);
        include_once 'inc/footer.php';
    }
}

?>

<?php include_once 'inc/plugins.php';?>

<script>
    jQuery(document).ready(function($) {
        if($("#count-1").length){
            $("#count-1").counterUp({
                delay: 10,
                time: 1000
            });
        }
        if($("#count-2").length){
            $("#count-2").counterUp({
                delay: 10,
                time: 1000
            });
        }
        if($("#count-3").length){
            $("#count-3").counterUp({
                delay: 10,
                time: 1000
            });
        }
        if($("#count-4").length){
            $("#count-4").counterUp({
                delay: 10,
                time: 1000
            });
        }

        $('#owl_1').owlCarousel({
            items: 4,
            autoplay:true,
            autoplayTimeout:2000,
            loop: true,
            margin: 10,
            callbacks: true,
            // URLhashListener: true,
            autoplayHoverPause: true,
            // startPosition: 'URLHash',
            responsiveClass:true,
            responsive:{
                0:{
                    items:2,
                    nav:true
                },
                600:{
                    items:3,
                    nav:false
                },
                1000:{
                    items:4,
                    nav:true,
                }
            }
        });
        $('#owl_2').owlCarousel({
            items: 6,
            autoplay:true,
            autoplaySpeed: 3000,
            autoplayTimeout:3000,
            loop: true,
            margin: 0,
            autoplayHoverPause: true,
            responsiveClass:true,
            dots: false,
            slideTransition: 'linear',
            responsive:{
                0:{
                    items:2,
                    nav:true
                },
                600:{
                    items:3,
                    nav:false
                },
                1000:{
                    items:6,
                    nav:true,
                }
            }
        });
    });
</script>

</body>
</html>