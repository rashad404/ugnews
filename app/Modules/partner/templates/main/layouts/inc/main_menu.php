<?php
use Helpers\Url;
use \Helpers\Session;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\WorkordersModel;

$admin_role = Session::get('user_session_role');


$menu_list[] = ['name'=>'Landlord panel', 'url'=>'main/index','icon'=>'home'];
$menu_list[] = ['name'=>'Calendar', 'url'=>'calendar/index','icon'=>'calendar-alt'];
$menu_list[] = ['name'=>'Payments', 'url'=>'balance/index','icon'=>'dollar-sign'];
$menu_list[] = ['name'=>'Tenants', 'url'=>'tenants/active','icon'=>'users'];
$menu_list[] = ['name'=>'Applications', 'url'=>'applications/index','icon'=>'clipboard-list'];
$menu_list[] = ['name'=>'Showings', 'url'=>'showings/index','icon'=>'eye'];
$menu_list[] = ['name'=>'SMS Messages', 'url'=>'sms/index','icon'=>'sms'];
$menu_list[] = ['name'=>'Guest Cards', 'url'=>'customers/index','icon'=>'id-card'];
$menu_list[] = ['name'=>'Work Orders', 'url'=>'workorders/index','icon'=>'hammer'];
$menu_list[] = ['name'=>'Apartments', 'url'=>'apartments/index','icon'=>'building'];
$menu_list[] = ['name'=>'Parkings', 'url'=>'parkings/index','icon'=>'car'];
$menu_list[] = ['name'=>'Bed statistics', 'url'=>'aptstats/beds','icon'=>'chart-bar'];
$menu_list[] = ['name'=>'Apartment statistics', 'url'=>'aptstats/index','icon'=>'chart-area'];
$menu_list[] = ['name'=>'Delinquencies', 'url'=>'aptstats/delinquencies','icon'=>'clock'];
$menu_list[] = ['name'=>'Appointments', 'url'=>'appointments/index','icon'=>'handshake'];
$menu_list[] = ['name'=>'Leases', 'url'=>'leases/index','icon'=>'copy'];
$menu_list[] = ['name'=>'Lease Templates', 'url'=>'leasetemplates/index','icon'=>'file-contract'];
$menu_list[] = ['name'=>'Notices', 'url'=>'notices/index','icon'=>'flag'];
$menu_list[] = ['name'=>'House Rules', 'url'=>'houserules/index','icon'=>'list-alt'];
$menu_list[] = ['name'=>'Inventory', 'url'=>'inventory/index','icon'=>'truck-loading'];
$menu_list[] = ['name'=>'Data Analysis', 'url'=>'dataanalysis/index','icon'=>'chart-pie'];
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