<?php
use Helpers\Hooks;
use Core\Language;
use Models\LanguagesModel;
use \Models\MenusModel;
use Helpers\Session;
use Models\UserModel;
use Helpers\Url;
use Helpers\Cookie;
use Models\VisitorsModel;

//Country settings
$_SETTINGS = [];
if(Cookie::has('set_region')===true){
    $_SETTINGS['region'] = Cookie::get('set_region');
}else{
    Cookie::set('set_region', DEFAULT_COUNTRY);
    $_SETTINGS['region'] = DEFAULT_COUNTRY;
}

//UniqueID set
VisitorsModel::uniqueID();

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
    <meta name="keywords" content='<?=$data['keywords']?>'>
    <meta name="description" content='<?=$data['description']?>'>
    <meta name="copyright" content="<?=SITE_NAME?>" />




    <meta property="og:title" content='<?=$data['title']?>'>
    <meta property="og:description" content='<?=$data['description']?>'>
    <?= isset($data['meta_img'])?'<meta property="og:image" content="https://'.$_SERVER['HTTP_HOST'].'/'.Url::uploadPath().$data['meta_img'].'">':'';?>

    <meta property="og:url" content="https://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>">
    <meta property="og:site_name" content="<?=SITE_NAME?>">


    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=Url::templatePath()?>img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=Url::templatePath()?>img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?=Url::templatePath()?>img/favicon/site.webmanifest">
<!--    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">-->


    <link href="<?=Url::templatePath()?>css/app.min.css<?=$css_v?>" rel="stylesheet" type="text/css">
    <link href="<?=Url::templatePath()?>css/tailwind-output.css<?=$css_v?>" rel="stylesheet" type="text/css">
    <link href="<?=Url::templatePath()?>css/bootstrap.min.css<?=$css_v?>" rel="stylesheet" type="text/css">
    <link href="<?=Url::templatePath()?>assets/fontawesome-55/css/all.min.css<?=$css_v?>" rel="stylesheet" type="text/css">
    <link href="<?=Url::templatePath()?>assets/owlcarousel/owl.carousel.min.css<?=$css_v?>" rel="stylesheet" type="text/css">
    <link href="<?=Url::templatePath()?>assets/owlcarousel/owl.theme.default.css<?=$css_v?>" rel="stylesheet" type="text/css">

    <title><?php echo $data['title']; ?></title>
</head>
<body class="no_overflow">

<script src='<?=Url::templatePath()?>js/jquery.min.js<?=$css_v?>' type="text/javascript"></script>
<script src='<?=Url::templatePath()?>js/bootstrap.min.js<?=$css_v?>' type="text/javascript"></script>
<script src='<?=Url::templatePath()?>assets/owlcarousel/owl.carousel.min.js<?=$css_v?>' type="text/javascript"></script>
<script src='<?=Url::templatePath()?>js/main.min.js<?=$css_v?>' type="text/javascript"></script>


<script>
    $(document).ready(function(){
        // $(".top_channel_carousel").owlCarousel();
        $('.top_channel_carousel').owlCarousel({
            loop:true,
            pagination: false,
            nav: false,
            dots: false,
            items: 5,

            autoplay : true,
            slideTransition: 'linear',
            autoplayTimeout : 5000,
            autoplayHoverPause : false,
            autoplaySpeed : 5000,

            margin:0,
            responsiveClass:true,
            responsive:{
                0:{
                    items:3,
                    nav:false
                },
                600:{
                    items:4,
                    nav:false
                },
                1000:{
                    items:5,
                    nav:false,
                    loop:false
                }
            }
        })
    });

</script>

<?php
$hooks->run('footer');
?>

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
<script src="<?=Url::templatePath()?>js/valyuta.js<?=$css_v?>"></script>
<script src="<?=Url::templatePath()?>js/weather.js<?=$css_v?>"></script>
</body>
</html>