<?php
use Models\LanguagesModel;
use Modules\partner\Models\AptStatsModel;
use Modules\partner\Models\TenantsModel;
use \Helpers\Session;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\RoomsModel;
$user_role = Session::get('partner_session_role');

$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();

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

//$sum_total = 'xx';
//$sum_active = 'xx';
//$sum_vacant = 'xx';
//$apt_cost = 'xx';
//$revenue = 'xx';
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">

    <div class="container-fluid">
        <div class="row">
            <?php if($user_role==1):?>
            <div class="col-xs-6">
                <div class="total_stats">
                    <div><span class="table_key"><?=$lng->get('Total Beds')?>:</span> <span class="table_value">+ <?=$total_beds?></span></div><div class="clearBoth"></div>
                    <div><span class="table_key"><?=$lng->get('Active Beds')?>:</span> <span class="table_value">- <?=$active_beds?></span></div><div class="clearBoth"></div>
                </div>
            </div>
            <?php endif;?>
            <div class="col-xs-6">
                <div class="total_stats">
                    <div>
                        <span class="table_key">
                            <a href="#notice_table"><?=$lng->get('Notice Beds')?></a>:
                        </span> <span class="table_value">- <?=$notice_beds?></span></div><div class="clearBoth"></div>
                    <div>
                        <span class="table_key"><a href="#vacant_table"><?=$lng->get('Vacant Beds')?></a>:</span>
                        <span class="table_value <?=($vacant_beds>0)?'red_color':''?>"> <?=$vacant_beds?></span>
                    </div>
                    <div class="clearBoth"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach ($data['apartments'] as $apartment):?>
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
                    $bed_list = BedsModel::getList($apartment['id']);

//                    $sum_active = 'xx';
//                    $sum_vacant = 'xx';
//                    $apt_cost = 'xx';
//                    $revenue = 'xx';
                ?>
                <div class="col-md-6">
                    <div class="apt_stats" style="color:red">
                        <a href="../beds/index/<?=$apartment['id']?>">
                            <div class="bed_box" style="border: 1px solid <?=($apartment['category']==1)?'#fcfffc':'#f9f1f0'?>">
                                <div class="apt_name_beds">
                                    <div style="margin-bottom: 10px;font-weight: bold">
                                    <?php if($apartment['category']==1):?>
                                            <i class="fa fa-male" style="color:#078061;position: absolute;right:20px;font-size: 50px"></i>
                                    <?php else: ?>
                                            <i class="fa fa-female" style="color:#ff6468;position: absolute;right:20px;font-size: 50px"></i>
                                    <?php endif;?>
                                         <?=$apartment['name']?>
                                    </div>
                                </div>

                                <table class="apt_bed_table">
                                <?php $c=1;foreach ($bed_list as $bed):
                                    $tenant_name = TenantsModel::getName($bed['tenant_id']);
                                    $tenant_break = TenantsModel::getBreakPredict($bed['tenant_id']);
                                    $break_reason_array = TenantsModel::getBreakPredicts();
                                    if($tenant_break>0){
                                        $break_reason = $break_reason_array[$tenant_break]['name'];
                                    }else{
                                        $break_reason = '';
                                    }
                                    if(empty(trim($tenant_name)))$tenant_name='<span class="vacant_bed">VACANT</span>';
                                    if(!empty($bed['available_date']))$tenant_name='<span class="notice_bed">'.$tenant_name.'</span>';
                                    ?>
                                        <tr>
                                            <td class="name">
                                                <?=$bed['room_name']?>
                                                <?=$bed['name_en']?>
                                            </td>
                                            <td>
                                                <?php if($tenant_break>0):?>
                                                    <span style="text-decoration: line-through;text-decoration-color: red;"><?=$tenant_name?></span> <i class="fa fa-ban" style="color:red;font-size: 18px;"></i>
                                                <?php else:?>
                                                    <?=\Helpers\Format::shortText($tenant_name,100)?>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                                <?=$bed['available_date']?> <?=$break_reason?>
                                            </td>
                                <?php $c++;endforeach;?>
                                </table>
                            </div>
                        </a>
                    </div>
                </div>

            <?php endforeach;?>
        </div>


        <div class="row">
            <div class="col-md-6 stats_bed_list" id="notice_table">
                <div class="title">Notice list</div>
                <table class="notice_table">
                    <th>Tenant name</th>
                    <th>Room</th>
                    <th>Apartment</th>
                    <th>Available date</th>
                    <th>Gender</th>
                    <?php

                    $model = new BedsModel();
                    $array = $model->getListNotice();
                    foreach ($array as $list){
                        $tenant_id = $list['tenant_id'];
                        $tenant_name = TenantsModel::getName($tenant_id);
                        $gender = TenantsModel::getGenderName($tenant_id);
                        ?>
                        <tr>
                            <td><?=$tenant_name?></td>
                            <td><?=$list['room_name']?></td>
                            <td><?=ApartmentsModel::getName($list['apt_id'])?></td>
                            <td><?=$list['available_date']?></td>
                            <?php
                            if($gender==1){
                                $gender_text = '<i class="fa fa-male" style="color:#078061;font-size: 22px;"></i>';
                            }else{
                                $gender_text = '<i class="fa fa-female" style="color:#ff6468;font-size: 22px;"></i>';
                            }
                            ?>
                            <td><?=$gender_text?></td>
                        </tr>

                    <?php } ?>
                </table>
            </div>
            <div class="col-md-6 stats_bed_list" id="vacant_table">
                <div class="title">Vacant bed list</div>
                <table class="notice_table">
                    <th>Bed name</th>
                    <th>Apartment</th>
                    <th>Gender</th>
                    <?php

                    $model = new BedsModel();
                    $array = $model->getListVacant();
                    foreach ($array as $list){
                        $tenant_id = $list['tenant_id'];
                        $tenant_name = TenantsModel::getName($tenant_id);
                        $gender = ApartmentsModel::getGenderName($list['apt_id']);
                        if($gender==1){
                            $gender_text = '<i class="fa fa-male" style="color:#078061;font-size: 22px;"></i>';
                        }else{
                            $gender_text = '<i class="fa fa-female" style="color:#ff6468;font-size: 22px;"></i>';
                        }
                        ?>
                        <tr>
                            <td><?=$list['room_name']?> <?=$list['name']?></td>
                            <td><?=ApartmentsModel::getName($list['apt_id'])?></td>
                            <td><?=$gender_text?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="col-md-6 stats_bed_list" id="vacant_table">
                <div class="title">Break Prediction list</div>
                <table class="notice_table">
                    <th>Tenant name</th>
                    <th>Room</th>
                    <th>Apartment</th>
                    <th>Gender</th>
                    <?php

                    $model = new TenantsModel();
                    $array = $model->getBreakPredictList();

                    foreach ($array as $list){
                        $tenant_id = $list['id'];
                        $tenant_name = $list['first_name'].' '.$list['last_name'];
                        $gender = ApartmentsModel::getGenderName($list['apt_id']);
                        $room_name = RoomsModel::getName($list['room_id']);
                        if($gender==1){
                            $gender_text = '<i class="fa fa-male" style="color:#078061;font-size: 22px;"></i>';
                        }else{
                            $gender_text = '<i class="fa fa-female" style="color:#ff6468;font-size: 22px;"></i>';
                        }
                        ?>
                        <tr>
                            <td><?=$tenant_name?></td>
                            <td><?=$room_name?></td>
                            <td><?=ApartmentsModel::getName($list['apt_id'])?></td>
                            <td><?=$gender_text?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>




    </div>
</section><!-- /.content -->