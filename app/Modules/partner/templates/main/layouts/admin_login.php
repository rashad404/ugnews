<?php
use Helpers\Assets;
use Helpers\Url;

?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?=MODULE_ADMIN_TITLE;?></title>
    <?php 
    Assets::css([
        Url::templatePartnerPath() . 'bootstrap/css/bootstrap.min.css',
        Url::templatePartnerPath() . 'font-awesome/css/font-awesome.min.css',
        Url::templatePartnerPath() . 'css/AdminLTE.css',
        Url::templatePartnerPath() . 'css/skins/_all-skins.css'
    ]);
    ?>
    <style>
        .alertim{
            position: fixed;
            z-index: 3;
            width: 40%;
            right: 5px;
            top: 5px;
            padding: 5px;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php eval($content)?>
<?php
Assets::js([
    Url::templatePartnerPath() . 'js/jquery.min.js',
    Url::templatePartnerPath() . 'bootstrap/js/bootstrap.min.js',
]);
?>
</body>
</html>