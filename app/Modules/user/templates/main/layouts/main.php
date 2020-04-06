<?php
use Helpers\Assets;
use Helpers\Session;
use Helpers\Url;
$user_id = Session::get('user_session_id');
$css_v = '?v='.UPDATE_VERSION;
?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>User Portal</title>
    <?php

    Assets::css([
        Url::templateUserPath() . 'bootstrap/css/bootstrap.min.css',
        Url::templateUserPath() . 'switch/css/bootstrap-switch.css',
        Url::templateUserPath() . 'slim/css/slim.css',
        Url::templateUserPath() . 'css/bootstrap-datetimepicker.min.css',
        Url::templateUserPath() . 'font-awesome/css/all.min.css'.$css_v,
        Url::templateUserPath() . 'css/AdminLTE.css'.$css_v,
        Url::templateUserPath() . 'css/skins/_all-skins.css'.$css_v,
        Url::templateUserPath() . 'css/style_r.css',
        Url::templateUserPath() . 'css/summernote.css',
        Url::templateUserPath() . 'semantic-ui/dropdown.min.css',
        Url::templateUserPath() . 'semantic-ui/transition.min.css',
        Url::templateUserPath() . 'photos/css/elan.css',
        Url::templateUserPath() . 'css/main.css'.$css_v,
    ]);
    ?>

    <?php
    Assets::js([
        Url::templateUserPath() . 'js/jquery.min.js',
        Url::templateUserPath() . 'bootstrap/js/bootstrap.min.js',
        Url::templateUserPath() . 'switch/js/bootstrap-switch.js',
        Url::templateUserPath() . 'slim/js/slim.kickstart.min.js',
        Url::templateUserPath() . 'js/app.min.js',
        Url::templateUserPath() . 'js/bootstrap-confirmation.min.js',
        Url::templateUserPath() . 'js/moment.js',
        Url::templateUserPath() . 'js/bootstrap-datetimepicker.min.js',
        Url::templateUserPath() . 'js/scr.js',
        Url::templateUserPath() . 'js/script_yuel.js',
        Url::templateUserPath() . 'js/summernote.min.js',
        Url::templateUserPath() . 'js/main.js',
        Url::templateUserPath() . 'semantic-ui/dropdown.min.js',
        Url::templateUserPath() . 'semantic-ui/transition.min.js',
        Url::templateUserPath() . 'photos/js/malsup.js',
        Url::templateUserPath() . 'photos/js/rotate.js',
        Url::templateUserPath() . 'photos/js/script.js',
    ]);

    ?>

    <script>
        $(document).ready(function() {
            $('#summernote,#summernote0,#summernote1,#summernote2').summernote({
                height: null,                 // set editor height
                minHeight: 100,             // set minimum height of editor
                maxHeight: 400,             // set maximum height of editor
                focus: true,                  // set focus to editable area after initializing summernote
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

    <!-- Header -->
        <?php include("inc/main_header.php"); ?>
    <!-- Header end -->

    <!-- Content Wrapper. Contains page content -->
    <?=($user_id>0)?'<div class="content-wrapper">':''?>

        <!-- View  -->
        <?php eval($content); ?>
        <!-- View  -->

    <?=($user_id>0)?'<div>':''?><!-- /.content-wrapper -->

</div><!-- ./wrapper -->

<input type="hidden" id="baseUrl" value="<?= Url::to(Url::getModuleController()) ?>"/>

</body>
</html>