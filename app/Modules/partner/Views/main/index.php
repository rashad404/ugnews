<?php

use Core\Language;
use Helpers\Csrf;
use Helpers\Format;
use Helpers\OperationButtons;
use Helpers\Url;
use Helpers\Session;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\AptStatsModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\CalendarModel;
use Modules\partner\Models\CustomersModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\ShowingsModel;
use Modules\partner\Models\TenantsModel;
use Modules\partner\Models\BalanceModel;
use Modules\partner\Models\ApplicationsModel;
$lng = new Language();
$lng->load('user');

new BalanceModel();
new AptStatsModel('');
$app_model = new ApplicationsModel();
$balance_logs = BalanceModel::getPayments();
$app_list = ApplicationsModel::getList('LIMIT 0,3');
$app_inputs = $app_model::getInputs();
    $params = [
        'name' => 'applications',
        'searchFields' => ['id','date','time','unit','note'],
        'title' => 'Applications',
        'position' => false,
        'status' => true,
        'actions' => true,
    ];


$delin_list =  AptStatsModel::getDelinquencies('LIMIT 0,3');


new CalendarModel();
$arrayMoveIn = CalendarModel::getListMoveIn();
$arrayMoveOut = CalendarModel::getListMoveOut();
$arrayShowings = CalendarModel::getListShowings();

function date_compare($a, $b)
{
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}
$calendar_list = array_merge($arrayMoveIn, $arrayMoveOut, $arrayShowings);


usort($calendar_list, 'date_compare');


$user_role = Session::get('user_session_role');

$apartment_list = ApartmentsModel::getActiveList();

$sum_total = AptStatsModel::sumPriceAll();
$sum_active = AptStatsModel::sumPriceActive();
$sum_vacant = AptStatsModel::sumVacantBeds();
//$sum_vacant = $sum_total - $sum_active;
$apt_cost = AptStatsModel::sumCostApt();
$expenses = $apt_cost + $sum_vacant;
$revenue = $sum_total - $apt_cost - $sum_vacant;

$total_beds = AptStatsModel::countBeds();
$active_beds = AptStatsModel::countBedsActive();
$notice_beds = AptStatsModel::countBedsNotice();
$vacant_beds = $total_beds - $active_beds - $notice_beds;

$user_role = Session::get('partner_session_role');

$menu_list_index[] = ['name'=>'Tenants', 'url'=>'tenants/index','icon'=>'users'];
$menu_list_index[] = ['name'=>'Applications', 'url'=>'applications/index','icon'=>'clipboard-list'];
$menu_list_index[] = ['name'=>'Showings', 'url'=>'showings/index','icon'=>'eye'];
$menu_list_index[] = ['name'=>'Guest Cards', 'url'=>'customers/index','icon'=>'id-card'];
$menu_list_index[] = ['name'=>'Apartments', 'url'=>'apartments/index','icon'=>'building'];
$menu_list_index[] = ['name'=>'Parkings', 'url'=>'parkings/index','icon'=>'car'];
$menu_list_index[] = ['name'=>'Bed statistics', 'url'=>'aptstats/beds','icon'=>'chart-bar'];
$menu_list_index[] = ['name'=>'Apartment statistics', 'url'=>'aptstats/index','icon'=>'chart-area'];
$menu_list_index[] = ['name'=>'Delinquencies', 'url'=>'aptstats/delinquencies','icon'=>'clock'];
$menu_list_index[] = ['name'=>'Calendar', 'url'=>'calendar/index','icon'=>'calendar-alt'];

$menu_list_index[] = ['name'=>'Appointments', 'url'=>'appointments/index','icon'=>'handshake'];
$menu_list_index[] = ['name'=>'Notices', 'url'=>'notices/index','icon'=>'flag'];
$menu_list_index[] = ['name'=>'Leases', 'url'=>'leases/index','icon'=>'copy'];
$menu_list_index[] = ['name'=>'Lease Templates', 'url'=>'leasetemplates/index','icon'=>'file-contract'];
$menu_list_index[] = ['name'=>'Inventory', 'url'=>'inventory/index','icon'=>'truck-loading'];
$menu_list_index[] = ['name'=>'Logout', 'url'=>'main/logout','icon'=>'sign-out-alt'];

?>

<!-- Main content -->
<section class="content">

    <div class="row">
        <?php if($user_role==1):?>
            <div class="col-xs-6">
                <div class="total_stats">
                    <div><span class="table_key"><?=$lng->get('Income')?>:</span> <span class="table_value">+ <?=$sum_total?> $</span></div><div class="clearBoth"></div>
                    <div><span class="table_key"><?=$lng->get('Expenses')?>:</span> <span class="table_value">- <?=$apt_cost?> $</span></div><div class="clearBoth"></div>
                    <div><span class="table_key"><?=$lng->get('Vacant')?>:</span> <span class="table_value">- <?=$sum_vacant?> $</span></div><div class="clearBoth"></div>
                    <div><span class="table_key"><?=$lng->get('Revenue')?>:</span><span class="table_value <?=($revenue>0)?'green_color':'red_color'?>"> <?=$revenue?> $</span></div><div class="clearBoth"></div>
                </div>
            </div>
        <?php endif;?>
        <div class="col-xs-6">
            <div class="total_stats">
                <div><span class="table_key"><?=$lng->get('Total Beds')?>:</span> <span class="table_value">+ <?=$total_beds?></span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Active Beds')?>:</span> <span class="table_value">- <?=$active_beds?></span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Notice Beds')?>:</span> <span class="table_value">- <?=$notice_beds?></span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Vacant Beds')?>:</span><span class="table_value <?=($vacant_beds>0)?'red_color':''?>"> <?=$vacant_beds?></span></div><div class="clearBoth"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($apartment_list as $apartment):?>
            <?php
            $all_beds = AptStatsModel::countAptBeds($apartment['id']);
            $active_beds = AptStatsModel::countAptTenantsByApt($apartment['id']);
            $notice_beds = AptStatsModel::countNoticeByApt($apartment['id']);
            $vacant_beds = $all_beds - $active_beds - $notice_beds;
            $tenants = TenantsModel::getListByApt($apartment['id']);
            $sum_total = AptStatsModel::sumPriceByAptAll($apartment['id']);
            $sum_active = AptStatsModel::sumPriceByAptActive($apartment['id']);
            $sum_vacant = $sum_total - $sum_active;
            $apt_rent = intval($apartment['rent']);
            $revenue = $sum_active - $apt_rent;

//                    $sum_active = 'xx';
//                    $sum_vacant = 'xx';
//                    $apt_cost = 'xx';
//                    $revenue = 'xx';


            ?>
            <div class="col-md-4">
                <div class="apt_stats">
                    <a href="/partner/beds/index/<?=$apartment['id']?>">
                        <div class="apt_box">
                            <div class="apt_name"><?=$apartment['name']?></div>
                            <div class="apt_address"><?=$apartment['address']?></div>
                            <div class="apt_stats_counts">
                                <?=($active_beds>0)?'<span class="count_active_beds">'.$active_beds.'</span>':''?>
                                <?=($notice_beds>0)?'<span class="count_notice_beds">'.$notice_beds.'</span>':''?>
                                <?=($vacant_beds>0)?'<span class="count_vacant_beds">'.$vacant_beds.'</span>':''?>
                            </div>
                            <div class="apt_tenants">
                                <?php $c=1;foreach ($tenants as $tenant):?>
                                    <span class="apt_counting"><?=$tenant['bed_name']?>.</span><?=$tenant['first_name']?>
                                    <?php $c++;endforeach;?>
                            </div>
                            <?php if($user_role==1):?>
                                <div class="apt_revenues">
                                    <div class="apt_revenue_item">+ <?=$sum_active?> $</div>
                                    <div class="apt_revenue_item">- <?=$apt_rent?> $</div>
                                    <div class="apt_revenue_item <?=($revenue>0)?'green_color':'red_color'?>">= <?=$revenue?> $</div>
                                </div>
                            <?php endif;?>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach;?>
    </div>

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to(MODULE_PARTNER."/".$params["name"]."/operation")?>" method="post">
                <div class="box">
                    <div class="half_box_title">
                        <a href="/partner/calendar/index" target="_blank"><?=$lng->get('Calendar')?> <i class="fas fa-external-link-square-alt"></i></a>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <?php if(!empty($calendar_list)):?>
                            <table class="default">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Apartment</th>
                                </tr>
                                <?php foreach($calendar_list  as $item){ ?>
                                    <?php

                                    if(isset($item['guest_id'])){
                                        new CustomersModel();
                                        if($item['guest_id']>0){
                                            $tenant_info = CustomersModel::getItem($item['guest_id']);
                                            $item['first_name'] = $tenant_info['first_name'];
                                            $item['last_name'] = $tenant_info['last_name'];
                                            $item['phone'] = $tenant_info['phone'];
                                            $tenant_link = '<a href="/partner/customers/view/'.$item["guest_id"].'">'.$item["first_name"].' '.$item["last_name"].'</a>';

                                        }elseif($item['user_id']>0){
                                            $tenant_info = TenantsModel::getItem($item['user_id']);
                                            $item['first_name'] = $tenant_info['first_name'];
                                            $item['last_name'] = $tenant_info['last_name'];
                                            $item['phone'] = $tenant_info['phone'];
                                            $tenant_link = '<a href="/partner/tenants/view/'.$item["user_id"].'">'.$item["first_name"].' '.$item["last_name"].'</a>';
                                        }
                                        $type = ShowingsModel::getTypes($item['type']);
                                        $view = '<a class="btn btn-xs btn-info" href="/partner/showings/update/'.$item['id'].'"><i class="fas fa-street-view"></i></a>';

                                    }else{
                                        if(isset($item['move_in'])){
                                            $type = 'Move in';
                                            $view = '<a class="btn btn-xs btn-success" href="/partner/tenants/view/'.$item["id"].'"><i class="fas fa-sign-in-alt"></i></a>';
                                        }else{
                                            $type = 'Move out';
                                            $view = '<a class="btn btn-xs btn-danger" href="/partner/tenants/view/'.$item["id"].'"><i class="fas fa-sign-out-alt"></i></a>';
                                        }
                                        $tenant_link = '<a href="/partner/tenants/view/'.$item["id"].'">'.$item["first_name"].' '.$item["last_name"].'</a>';
                                    }
                                    ?>
                                    <tr>
                                        <td style="width: 50px;">
                                            <?=$view?>
                                        </td>
                                        <td>
                                            <?= $tenant_link?><br/>
                                            <div class="list_alt_text">
                                                <i class="fa fa-phone"></i> <span style="color:#496086;cursor:pointer;" onclick="copyFunction()"><?=Format::phoneNumber($item['phone'])?></span>
                                            </div>
                                        </td>
                                        <?php
                                        $time = strtotime($item["date"]);

                                        $datetime = new DateTime('tomorrow');
                                        $tomorrow = $datetime->format('Y-m-d');

                                        if($item["date"] == date("Y-m-d")) {
                                            $date_new_format = '<span style="color:#ff0707;font-weight: bold;">Today</span>';
                                        }elseif(date("Y-m-d",$time) == date("Y-m-d")) {
                                            $date_new_format = '<span style="color:#ff0707;font-weight: bold;">'.date('h:i A', $time).'</span>';
                                        }elseif(date("Y-m-d", $time) == $tomorrow) {
                                            $date_new_format = '<span style="color:#147bda;font-weight: bold;">Tomorrow</span>';
                                        }else{
                                            $date_new_format = date('M j',$time);
                                        }
                                        $apartment_name = ApartmentsModel::getName($item['apt_id']);
                                        $room_name = RoomsModel::getName($item['room_id']);
                                        $bed_name = BedsModel::getName($item['bed_id']);
                                        $apt_name = $apartment_name.', '.$room_name.' '.$bed_name;

                                        ?>
                                        <td><?= $date_new_format?></td>
                                        <td><?= $type?></td>
                                        <td><?= $apt_name?></td>


                                    </tr>
                                <?php } ?>
                            </table>
                            <?php else:?>
                            <div class="no_data"><?=$lng->get('You don\'t have any event.')?></div>
                            <?php endif;?>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->


    <div class="row">
        <div class="col-sm-12">
            <div class="half_box_with_title">
                <div class="half_box_title">
                    <a href="/partner/balance/index" target="_blank"><?=$lng->get('Payments')?> <i class="fas fa-external-link-square-alt"></i></a>
                </div>
                <div class="half_box_body table-responsive">
                    <table class="default">
                        <tr>
                            <th><?=$lng->get('Date')?></th>
                            <th><?=$lng->get('Tenant')?></th>
                            <th><?=$lng->get('Amount')?> (<?=DEFAULT_CURRENCY_SHORT?>)</th>
                            <th><?=$lng->get('Method')?></th>
                            <th><?=$lng->get('Receipt')?></th>
                        </tr>
                        <?php foreach ($balance_logs as $data):?>
                            <tr>
                                <td><?=date('m/d/Y H:i',$data['time'])?></td>
                                <td><?=$data['first_name']?> <?=$data['last_name']?></td>
                                <td><?=abs($data['amount'])?></td>
                                <td><?=$data['description']?></td>
                                <td>
                                    <?php if($data['action']=='receipt'):?>
                                        <form action="" method="post">
                                            <input type="hidden" value="<?=$data['id']?>" name="log_id">
                                            <input type="hidden" value="<?=Csrf::makeToken($data['id']);?>" name="csrf_token<?=$data['id']?>">
                                            <button type="submit" class="btn btn-primary"><?=$lng->get('Send Receipt')?></button>
                                        </form>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="half_box_with_title">
                <div class="half_box_title">
                    <a href="/partner/applications/index" target="_blank"><?=$lng->get('Latest Applications')?> <i class="fas fa-external-link-square-alt"></i></a>
                </div>
                <div class="half_box_body table-responsive">

                    <table id="datatable2" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="width-20">#</th>
                                <?php foreach ($app_inputs as $item):?>
                                    <?php if($item['index']):?><th><?=$item['name']?></th><?php endif;?>
                                <?php endforeach;?>
                                <?php if($params["position"]){ ?><th><?=$lng->get('Order')?></th><?php } ?>
                                <?php if($params["actions"]){ ?><th><?=$lng->get('Actions')?></th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($app_list  as $item){ ?>
                            <tr>
                                <td class="admin-arrow-box width-20"><?= $item["id"]?></td>
                                <?php foreach ($app_inputs as $input_item):?>
                                    <?php if($input_item['index']):?>
                                        <?php
                                        if($input_item['type']=='datetime-local'){
                                            $input_value = date('m/d/Y h:i A', $item[$input_item['key']]);
                                        }else{
                                            $input_value = $item[$input_item['key']];
                                        }
                                        if($input_item['key']=='apt_id'){
                                            $input_value = ApartmentsModel::getName($input_value);
                                        }elseif($input_item['key']=='user_id'){
                                            $tenant_info = TenantsModel::getItem($input_value);
                                            $input_value = $tenant_info['first_name'].' '.$tenant_info['last_name'].'<br/>';
                                            $input_value .= '<span style="color:#496086;cursor:pointer;" onclick="copyFunction()">' .Format::phoneNumber($tenant_info['phone']).'</span>';

                                        }elseif($input_item['key']=='date'){
                                            $input_value = date("M d", strtotime($input_value));
                                        }elseif($input_item['key']=='time'){
                                            if(empty($input_value)){
                                                $input_value='All Day';
                                            }
                                            else {
                                                $input_value = date("g:i A", strtotime($input_value));
                                            }
                                        }elseif($input_item['key']=='type'){
                                            $input_value = ShowingsModel::getTypes($input_value);
                                        }
                                        ?>
                                        <td class="admin-arrow-box"><?= $input_value?></td>
                                    <?php endif;?>
                                <?php endforeach;?>
                                <?php $opButtons = new OperationButtons();?>
                                <?php if($params["position"]){ ?>
                                    <td class="admin-arrow-box"> <?= $opButtons->getPositionIcons($item["id"],MODULE_PARTNER."/".$params["name"])?></td>
                                <?php } ?>
                                <?php if($params["actions"]){ ?>
                                    <td class="admin-arrow-box">
                                        <?= $opButtons->getCrudIcons($item["id"],MODULE_PARTNER."/".$params["name"])?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="half_box_with_title">
                <div class="half_box_title">
                    <a href="/partner/aptstats/delinquencies" target="_blank"><?=$lng->get('Delinquencies')?> <i class="fas fa-external-link-square-alt"></i></a>
                </div>
                <div class="half_box_body table-responsive">
                    <table class="default">
                        <tr>
                            <th><?=$lng->get('Tenant')?></th>
                            <th><?=$lng->get('Balance')?> (<?=DEFAULT_CURRENCY_SHORT?>)</th>
                        </tr>
                        <?php foreach ($delin_list as $data):?>
                            <tr>
                                <td>
                                    <a href="/partner/tenants/view/<?= $data["id"]?>"><?= $data["first_name"]?> <?= $data["last_name"]?></a><br/>
                                    <div class="list_alt_text">
                                        <i class="fa fa-phone"></i> <span style="color:#496086;cursor:pointer;" onclick="copyFunction()"><?=Format::phoneNumber($data['phone'])?></span> <i class="fa fa-envelope"></i> <?= $data["email"]?>
                                    </div>
                                </td>
                                <td><?=$data['balance']?></td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div class="clearBoth"></div>
</section>



<script>
    function copyFunction() {
        const $temp = $("<input>");
        $("body").append($temp);
        const copyText = event.target.innerHTML;
        $temp.val(copyText).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied: '+copyText);
    }
</script>