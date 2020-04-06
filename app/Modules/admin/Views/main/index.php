<?php
use Helpers\Url;
use Helpers\Session;
$admin_role = Session::get('auth_session_role');

if($admin_role==1) $menu_list_index[] = ['name'=>'Apartments', 'url'=>'apartments/index','icon'=>'menu-icon.png'];
$menu_list_index[] = ['name'=>'Bed statistics', 'url'=>'aptstats/beds','icon'=>'menu-icon.png'];
$menu_list_index[] = ['name'=>'Apartment statistics', 'url'=>'aptstats/index','icon'=>'menu-icon.png'];
$menu_list_index[] = ['name'=>'Calendar', 'url'=>'calendar/index','icon'=>'calendar-icon.png'];
$menu_list_index[] = ['name'=>'Tenants', 'url'=>'tenants/index','icon'=>'menu-icon.png'];
$menu_list_index[] = ['name'=>'Guest Cards', 'url'=>'customers/index','icon'=>'menu-icon.png'];
$menu_list_index[] = ['name'=>'Showings', 'url'=>'showings/index','icon'=>'menu-icon.png'];
$menu_list_index[] = ['name'=>'Appointments', 'url'=>'appointments/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list_index[] = ['name'=>'Notices', 'url'=>'notices/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list_index[] = ['name'=>'Notice Templates', 'url'=>'noticetemplates/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list_index[] = ['name'=>'Inventory', 'url'=>'inventory/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list_index[] = ['name'=>'Blog', 'url'=>'blog/index','icon'=>'menu-icon.png'];
if($admin_role==1) $menu_list_index[] = ['name'=>'Menus', 'url'=>'menus/index','icon'=>'menu-icon.png'];

?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span>Admin</span>
    </div>
</section>

<!-- Main content -->
<section class="content">

    <?php foreach ($menu_list_index as $menu): ?>
        <div class="col-lg-3 mar-top-40">
            <div class="panelitembox" onclick="javascript:window.location.href = '<?= Url::to(MODULE_ADMIN.'/'.$menu['url'])?>'">
                <div class="panelitemiconbox orange"><img src="<?= Url::templateModulePath() ?>icons/<?=$menu['icon']?>"></div>

                <span class="panelitemyazi"><?=$menu['name']?></span>
                <span class="panelitemgoz"><img src="<?= \Helpers\Url::templateModulePath() ?>icons/view-ic.png"></span>
            </div> <!-- item end -->
        </div>

    <?php endforeach; ?>


</section>
