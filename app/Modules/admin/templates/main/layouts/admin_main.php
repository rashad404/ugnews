<?php
use Helpers\Assets;
use Helpers\Url;

?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= MODULE_ADMIN_TITLE; ?></title>
    <?php
    Assets::css([
        Url::templateModulePath() . 'bootstrap/css/bootstrap.min.css',
        Url::templateModulePath() . 'switch/css/bootstrap-switch.css',
        Url::templateModulePath() . 'slim/css/slim.css',
        Url::templateModulePath() . 'css/bootstrap-datetimepicker.min.css',
        Url::templateModulePath() . 'font-awesome/css/font-awesome.min.css',
        Url::templateModulePath() . 'css/AdminLTE.css',
        Url::templateModulePath() . 'css/skins/_all-skins.css',
        Url::templateModulePath() . 'css/style_r.css',
        Url::templateModulePath() . 'css/summernote.css',
        Url::templateModulePath() . 'semantic-ui/dropdown.min.css',
        Url::templateModulePath() . 'semantic-ui/transition.min.css',
        Url::templateModulePath() . 'photos/css/elan.css',
        Url::templateModulePath() . 'css/main.css?'.rand(1111111,9999999),
    ]);
    ?>

    <?php
    Assets::js([
        Url::templateModulePath() . 'js/jquery.min.js',
        Url::templateModulePath() . 'bootstrap/js/bootstrap.min.js',
        Url::templateModulePath() . 'switch/js/bootstrap-switch.js',
        Url::templateModulePath() . 'slim/js/slim.kickstart.min.js',
        Url::templateModulePath() . 'js/app.min.js',
        Url::templateModulePath() . 'js/bootstrap-confirmation.min.js',
        Url::templateModulePath() . 'js/moment.js',
        Url::templateModulePath() . 'js/bootstrap-datetimepicker.min.js',
        Url::templateModulePath() . 'js/scr.js',
        Url::templateModulePath() . 'js/script_yuel.js',
        Url::templateModulePath() . 'js/summernote.min.js',
        Url::templateModulePath() . 'js/main.js',
        Url::templateModulePath() . 'semantic-ui/dropdown.min.js',
        Url::templateModulePath() . 'semantic-ui/transition.min.js',
        Url::templateModulePath() . 'photos/js/malsup.js',
        Url::templateModulePath() . 'photos/js/rotate.js',
        Url::templateModulePath() . 'photos/js/script.js',
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
    <div class="content-wrapper">

        <!-- View  -->
        <?php eval($content); ?>
        <!-- View  -->

    </div><!-- /.content-wrapper -->

</div><!-- ./wrapper -->

<input type="hidden" id="baseUrl" value="<?= Url::to(Url::getModuleController()) ?>"/>

</body>
</html>