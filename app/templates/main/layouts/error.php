<?php
use Helpers\Assets;
use Helpers\Url;
use Helpers\Hooks;
$hooks = Hooks::get();
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <base href="<?=DIR?>" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="<?=SITE_NAME?>">
    <?php $hooks->run('meta'); ?>
    <?php
    Assets::css(array(
        Url::templatePath().'css/bootstrap.min.css',
        Url::templatePath().'css/fancb/jquery.fancybox.css',
        Url::templatePath().'css/touch-sideswipe.css',
        Url::templatePath().'css/owl.carousel.css',
        Url::templatePath().'css/owl.theme.css',
    ));
    $hooks->run('css');

    ?>
</head>
<body>
<?php eval($content) ?>
<?php
Assets::js(array(
    Url::templatePath() . 'js/jquery-2.1.4.min.js',
    Url::templatePath() . 'js/bootstrap.min.js',
    Url::templatePath() . 'js/masonry.min.js',
    Url::templatePath() . 'js/jquery.fancybox.js',
    Url::templatePath() . 'js/touch-sideswipe.js',
    Url::templatePath() . 'js/owl.carousel.min.js',
    Url::templatePath() . 'js/main.js',
));
$hooks->run('js');
$hooks->run('footer');
?>
</body>
</html>