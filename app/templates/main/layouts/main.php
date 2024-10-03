<?php
use Helpers\Hooks;
use Core\Language;
use Models\LanguagesModel;
use Models\MenusModel;
use Helpers\Session;
use Models\UserModel;
use Helpers\Url;
use Helpers\Cookie;
use Models\VisitorsModel;

// Country settings
$_SETTINGS = [];
if (Cookie::has('set_region') === true) {
    $_SETTINGS['region'] = Cookie::get('set_region');
} else {
    Cookie::set('set_region', DEFAULT_COUNTRY);
    $_SETTINGS['region'] = DEFAULT_COUNTRY;
}

// UniqueID set
VisitorsModel::uniqueID();

$css_v = '?v=' . UPDATE_VERSION;
$lng = new Language();
$lng->load('app');

$hooks = Hooks::get();

$userId = intval(Session::get("user_session_id"));

if ($userId > 0) {
    // Timeout for inactivity
    if (Session::get("timestamp") > 0 && time() - Session::get("timestamp") > LOGOUT_TIME) {
        Session::destroy();
        header("Location: " . DIR);
        exit;
    } else {
        Session::set("timestamp", time());
    }

    $userModel = new UserModel();
    $userInfo = $userModel->getInfo($userId);
    if ($userInfo['block'] == 1) {
        echo $lng->get("Your account has been blocked");
        exit;
    } else {
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
<html lang="<?= $data['defLang'] ?>" class="h-full bg-gray-100">
<head>
    <base href="<?=DIR?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <meta name="description" content="<?= $data['description'] ?>">
    <meta name="keywords" content="<?= $data['keywords'] ?>">
    <meta property="og:title" content="<?= $data['title'] ?>">
    <meta property="og:description" content="<?= $data['description'] ?>">
    <?= isset($data['meta_img']) ? '<meta property="og:image" content="https://'.$_SERVER['HTTP_HOST'].'/'.Url::uploadPath().$data['meta_img'].'">' : '' ?>
    <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    <link rel="icon" href="<?= Url::templatePath() ?>img/favicon/favicon-32x32.png" type="image/png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="<?= Url::templatePath() ?>css/tailwind-output.css?<?=$css_v?>">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>
<body class="h-full">
    <div x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="min-h-full">
        <?php include 'inc/header.php'; ?>

        <main>
            <div class="max-w-7xl mx-auto py-4 lg:py-6 px-2 sm:px-6 lg:px-8">
                <?php eval($content); ?>
            </div>
        </main>

        <?php include 'inc/footer.php'; ?>
    </div>

    
</body>
</html>