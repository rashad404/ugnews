<?php

use Core\Language;
use Helpers\Url;
use \Helpers\Session;

$lng = \Models\LanguagesModel::getLanguages();
$lng = new Language();
$lng->load('partner');

$admin_role = Session::get('user_session_role');


$menu_list[] = ['name'=>'Admin panel', 'url'=>'main/index','icon'=>'home'];
$menu_list[] = ['name'=>$lng->get('News'), 'url'=>'news/index','icon'=>'newspaper'];
$menu_list[] = ['name'=>$lng->get('Channels'), 'url'=>'channels/index','icon'=>'broadcast-tower'];
$menu_list[] = ['name'=>$lng->get('Settings'), 'url'=>'settings/defaults','icon'=>'tools'];
$menu_list[] = ['name'=>$lng->get('Logout'), 'url'=>'main/logout','icon'=>'sign-out-alt'];


?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu asidmenu">
            <?php foreach ($menu_list as $menu): ?>
            <li class="treeview">
                <a href="<?php echo Url::to('partner/'.$menu['url'])?>">
                    <i class="fas fa-<?=$menu['icon']?>"></i> <span><?=$menu['name']?></span>
                </a>

            </li>
            <?php endforeach; ?>

            <?php if(\Helpers\Session::get('auth_session_role') == 1 || \Helpers\Session::get('auth_session_role') == 2) { ?>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>