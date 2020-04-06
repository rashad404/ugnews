<?php
use Models\LanguagesModel;
use Modules\admin\Models\AptStatsModel;
use Modules\admin\Models\TenantsModel;
use \Helpers\Session;
$admin_role = Session::get('auth_session_role');

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
            <?php if($admin_role==1):?>
            <div class="col-lg-3 col-xs-6 total_stats">
                <div><span class="table_key"><?=$lng->get('Income')?>:</span> <span class="table_value">+ <?=$sum_total?> $</span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Expenses')?>:</span> <span class="table_value">- <?=$apt_cost?> $</span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Vacant beds')?>:</span> <span class="table_value">- <?=$sum_vacant?> $</span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Revenue')?>:</span><span class="table_value <?=($revenue>0)?'green_color':'red_color'?>"> <?=$revenue?> $</span></div><div class="clearBoth"></div>
            </div>
            <?php endif;?>
            <div class="col-lg-3 col-xs-6 total_stats">
                <div><span class="table_key"><?=$lng->get('Total Beds')?>:</span> <span class="table_value">+ <?=$total_beds?></span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Active Beds')?>:</span> <span class="table_value">- <?=$active_beds?></span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Notice Beds')?>:</span> <span class="table_value">- <?=$notice_beds?></span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Vacant Beds')?>:</span><span class="table_value <?=($vacant_beds>0)?'red_color':''?>"> <?=$vacant_beds?></span></div><div class="clearBoth"></div>
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

//                    $sum_active = 'xx';
//                    $sum_vacant = 'xx';
//                    $apt_cost = 'xx';
//                    $revenue = 'xx';


                ?>
                <div class="col-md-4">
                    <div class="apt_stats">
                        <a href="../beds/index/<?=$apartment['id']?>">
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
                            <?php if($admin_role==1):?>
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
    </div>
</section><!-- /.content -->