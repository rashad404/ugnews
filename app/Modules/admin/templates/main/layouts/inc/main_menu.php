<?php
use Helpers\Url;
use \Helpers\Session;
$admin_role = Session::get('auth_session_role');

$menu_list[] = ['name'=>'Admin panel', 'url'=>'main/index','icon'=>'panel-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Apartments', 'url'=>'apartments/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Apartment Future', 'url'=>'aptfuture/index','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Parkings', 'url'=>'parkings/index','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Bed statistics', 'url'=>'aptstats/beds','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Apartment statistics', 'url'=>'aptstats/index','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Calendar', 'url'=>'calendar/index','icon'=>'calendar-icon.png'];
$menu_list[] = ['name'=>'Tenants', 'url'=>'tenants/index','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Guest Cards', 'url'=>'customers/index','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Showings', 'url'=>'showings/index','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Appointments', 'url'=>'appointments/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Notices', 'url'=>'notices/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Notice Templates', 'url'=>'noticetemplates/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Inventory', 'url'=>'inventory/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Blog', 'url'=>'blog/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Slider', 'url'=>'slider/index','icon'=>'slider-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Testimonials', 'url'=>'testimonials/index','icon'=>'slider-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Texts', 'url'=>'texts/index','icon'=>'texts-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Seo Texts', 'url'=>'seotexts/index','icon'=>'texts-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'FAQs', 'url'=>'faqs/index','icon'=>'texts-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Menus', 'url'=>'menus/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'About us', 'url'=>'about/update/1','icon'=>'texts-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'Contact', 'url'=>'contacts/update/1','icon'=>'contact-icon.png'];
if($admin_role==1) $menu_list[] = ['name'=>'System parameters', 'url'=>'params/update/1','icon'=>'menu-icon.png'];
$menu_list[] = ['name'=>'Logout', 'url'=>'main/logout','icon'=>'logout-icon.png'];

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu asidmenu">
            <?php foreach ($menu_list as $menu): ?>
            <li class="treeview">
                <a href="<?php echo Url::to(MODULE_ADMIN.'/'.$menu['url'])?>"><img class="menuicon" src="<?= Url::templateModulePath() ?>icons/<?=$menu['icon']?>"> <span><?=$menu['name']?></span></a>
            </li>
            <?php endforeach; ?>

            <?php if(\Helpers\Session::get('auth_session_role') == 1 || \Helpers\Session::get('auth_session_role') == 2) { ?>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>