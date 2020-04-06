<?php
use Helpers\Url;
use \Helpers\Session;
$admin_role = Session::get('user_session_role');

$menu_list[] = ['name'=>'Online Portal', 'url'=>'main/index','icon'=>'home'];
$menu_list[] = ['name'=> 'Pay Online <span style="color:#ff3f44;font-weight: bold;">NEW</span>', 'url'=>'payments/index','icon'=>'money-check-alt'];
$menu_list[] = ['name'=>'Housemates', 'url'=>'housemates/index','icon'=>'user-friends'];
$menu_list[] = ['name'=>'House Rules', 'url'=>'houserules/index','icon'=>'list-alt'];
$menu_list[] = ['name'=>'Work Orders', 'url'=>'workorders/index','icon'=>'tools'];
$menu_list[] = ['name'=>'Notices', 'url'=>'notices/index','icon'=>'exclamation-circle'];
$menu_list[] = ['name'=>'Payment History', 'url'=>'balance/index','icon'=>'money-bill-alt'];
$menu_list[] = ['name'=>'Leases', 'url'=>'leases/index','icon'=>'copy'];
$menu_list[] = ['name'=>'Profile', 'url'=>'main/profile','icon'=>'address-card', 'target'=>true];
$menu_list[] = ['name'=>'Privacy', 'url'=>'settings/privacy','icon'=>'lock'];
$menu_list[] = ['name'=>'Logout', 'url'=>'main/logout','icon'=>'sign-out-alt'];

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu asidmenu">
            <?php foreach ($menu_list as $menu): ?>
            <li class="treeview">
                <a <?=(isset($menu['target']))?'target="_blank"':''?> href="<?php echo Url::to('user/'.$menu['url'])?>"><i class="fas fa-<?=$menu['icon']?>"></i> <span><?=$menu['name']?></span></a>
            </li>
            <?php endforeach; ?>

            <?php if(Session::get('auth_session_role') == 1 || Session::get('auth_session_role') == 2) { ?>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>