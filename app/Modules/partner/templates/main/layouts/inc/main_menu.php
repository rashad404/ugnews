<?php
use Helpers\Url;
use \Helpers\Session;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\WorkordersModel;

$admin_role = Session::get('user_session_role');


$menu_list[] = ['name'=>'Admin panel', 'url'=>'main/index','icon'=>'home'];
$menu_list[] = ['name'=>'News', 'url'=>'news/index','icon'=>'newspaper'];
$menu_list[] = ['name'=>'Channels', 'url'=>'channels/index','icon'=>'broadcast-tower'];
$menu_list[] = ['name'=>'Settings', 'url'=>'settings/defaults','icon'=>'tools'];
//$menu_list[] = ['name'=>'Payments', 'url'=>'balance/index','icon'=>'dollar-sign'];
$menu_list[] = ['name'=>'Logout', 'url'=>'main/logout','icon'=>'sign-out-alt'];

new SmsModel();
$new_messages = SmsModel::countNewMessages();
if ($new_messages == 0) $new_messages = '';

new WorkordersModel();
$new_work_orders = WorkordersModel::countNew();
if ($new_work_orders == 0) $new_work_orders = '';

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
                    <?php if($menu['url'] == 'sms/index'):?>
                        <span class="new_message_alert"><?=$new_messages?></span>
                    <?php endif;?>
                    <?php if($menu['url'] == 'workorders/index'):?>
                        <span class="new_message_alert"><?=$new_work_orders?></span>
                    <?php endif;?>
                </a>

            </li>
            <?php endforeach; ?>

            <?php if(\Helpers\Session::get('auth_session_role') == 1 || \Helpers\Session::get('auth_session_role') == 2) { ?>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>