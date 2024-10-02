<?php

use Core\Language;
use Helpers\Assets;
use Helpers\Cookie;
use Helpers\Session;
use Helpers\Url;
use Models\UserModel;

$user_id = $userId = Session::get('user_session_id');
$css_v = '?v='.UPDATE_VERSION;
$lng = new Language();
$lng->load('app');

//Country settings
$_SETTINGS = [];
if(Cookie::has('set_region')===true){
    $_SETTINGS['region'] = Cookie::get('set_region');
}else{
    Cookie::set('set_region', DEFAULT_COUNTRY);
    $_SETTINGS['region'] = DEFAULT_COUNTRY;
}

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
$_PARTNER = [];


Cookie::set('partner_id', 1);
$_PARTNER['id'] = 1;

$_PARTNER = \Models\PartnerModel::getInfo($_PARTNER['id']);

?>

<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Admin Panel</title>
    <?php

    Assets::css([
        Url::templatePartnerPath() . 'bootstrap/css/bootstrap.min.css',
        Url::templatePartnerPath() . 'switch/css/bootstrap-switch.css',
        Url::templatePartnerPath() . 'slim/css/slim.css',
        Url::templatePartnerPath() . 'css/bootstrap-datetimepicker.min.css',
        Url::templatePartnerPath() . 'font-awesome/css/all.min.css'.$css_v,
        Url::templatePartnerPath() . 'css/AdminLTE.css'.$css_v,
        Url::templatePartnerPath() . 'css/skins/_all-skins.css'.$css_v,
        Url::templatePartnerPath() . 'css/style_r.css',
        Url::templatePartnerPath() . 'css/main.css'.$css_v,//date picker icon size REMOVE
        Url::templatePath() . 'css/app.min.css'.$css_v,//header, left menu REMOVE
    ]);
    ?>

    <?php
    Assets::js([
        Url::templatePartnerPath() . 'js/jquery.min.js',
        Url::templatePartnerPath() . 'bootstrap/js/bootstrap.min.js',
        Url::templatePartnerPath() . 'switch/js/bootstrap-switch.js',
        Url::templatePartnerPath() . 'slim/js/slim.kickstart.min.js',
        Url::templatePartnerPath() . 'js/bootstrap-confirmation.min.js',
        Url::templatePartnerPath() . 'js/moment.js',//tagsinput
        Url::templatePartnerPath() . 'js/bootstrap-datetimepicker.min.js',
        Url::templatePartnerPath() . 'js/scr.js',//status on off
        Url::templatePartnerPath() . 'js/script_yuel.js',// select all
        Url::templatePartnerPath() . 'js/summernote.min.js',
        Url::templatePartnerPath() . 'js/bootstrap-tagsinput.min.js',
    ]);

    ?>

</head>
<body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">
        <?php include "app/templates/main/layouts/user_panel/header.php";?>

        <div class="content-wrapper">
            <?php eval($content); ?>
        </div>
        <div class="clearBoth">
    </div>

    <input type="hidden" id="baseUrl" value="<?= Url::to(Url::getModuleController()) ?>"/>

</body>
</html>