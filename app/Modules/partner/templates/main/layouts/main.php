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
        Url::templatePartnerPath() . 'css/summernote.css',
        Url::templatePartnerPath() . 'semantic-ui/dropdown.min.css',
        Url::templatePartnerPath() . 'semantic-ui/transition.min.css',
        Url::templatePartnerPath() . 'photos/css/elan.css',
        Url::templatePartnerPath() . 'css/main.css'.$css_v,
        Url::templatePath() . 'css/app.css'.$css_v,
        Url::templatePartnerPath() . 'css/bootstrap-tagsinput.css',
    ]);
    ?>

    <?php
    Assets::js([
        Url::templatePartnerPath() . 'js/jquery.min.js',
        Url::templatePartnerPath() . 'bootstrap/js/bootstrap.min.js',
        Url::templatePartnerPath() . 'switch/js/bootstrap-switch.js',
        Url::templatePartnerPath() . 'slim/js/slim.kickstart.min.js',
        Url::templatePartnerPath() . 'js/app.min.js',
        Url::templatePartnerPath() . 'js/bootstrap-confirmation.min.js',
        Url::templatePartnerPath() . 'js/moment.js',
        Url::templatePartnerPath() . 'js/bootstrap-datetimepicker.min.js',
        Url::templatePartnerPath() . 'js/scr.js',
        Url::templatePartnerPath() . 'js/script_yuel.js',
        Url::templatePartnerPath() . 'js/summernote.min.js',
        Url::templatePartnerPath() . 'js/main.js',
        Url::templatePath() . 'js/main.js'.$css_v,
        Url::templatePartnerPath() . 'js/bootstrap-tagsinput.min.js',
        Url::templatePartnerPath() . 'semantic-ui/dropdown.min.js',
        Url::templatePartnerPath() . 'semantic-ui/transition.min.js',
        Url::templatePartnerPath() . 'photos/js/malsup.js',
        Url::templatePartnerPath() . 'photos/js/rotate.js',
        Url::templatePartnerPath() . 'photos/js/script.js',
    ]);

    ?>

    <script>
        $(document).ready(function() {
            $('#summernote,#summernote0,#summernote1,#summernote2').summernote({
                height: null,                 // set editor height
                minHeight: 100,             // set minimum height of editor
                maxHeight: 400,             // set maximum height of editor
                focus: true,                  // set focus to editable area after initializing summernote
                fontSizes: ['6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30', '36', '48' , '64', '82', '150'],
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    // ['help', ['help']]
                ],
            });
        });
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

    <?php

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
    <!-- Header -->
        <?php include("inc/main_header.php"); ?>
    <!-- Header end -->

    <!-- Content Wrapper. Contains page content -->
    <?=($user_id>0)?'<div class="content-wrapper">':''?>

        <!-- View  -->
        <?php eval($content); ?>
        <!-- View  -->

    <?=($user_id>0)?'<div><div class="clearBoth"':''?><!-- /.content-wrapper -->

</div><!-- ./wrapper -->

<input type="hidden" id="baseUrl" value="<?= Url::to(Url::getModuleController()) ?>"/>

</body>
</html>