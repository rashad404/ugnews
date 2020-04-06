<?php

use Core\Language;
use Helpers\Date;
use Helpers\Features;
use Helpers\Format;
use Helpers\Url;
use Models\LanguagesModel;

$lng = new Language();
$lng->load('user');

$user_data = $data['user_data'];
$new_notices = $data['new_notices'];

$menu_list_index[] = ['name'=>'Work Orders', 'url'=>'workorders/index','icon'=>'tools'];
$menu_list_index[] = ['name'=>'Notices', 'url'=>'notices/index','icon'=>'exclamation-circle'];
$menu_list_index[] = ['name'=>'Payment History', 'url'=>'balance/index','icon'=>'money-bill-alt'];
$menu_list_index[] = ['name'=>'Leases', 'url'=>'leases/index','icon'=>'copy'];
$menu_list_index[] = ['name'=>'Housemates', 'url'=>'housemates/index','icon'=>'user-friends'];

?>
<!-- Content Header (Page header) -->
<section class="content-header">

    <div class="row header_info_user">

        <div class="col-sm-3">
            <div class="user_image">
                <img src="<?= URL::getUserImage($user_data['id'],$user_data['gender'])?>" alt="">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="user_info_center">
                <div class="user_name">
                    <?= $user_data["first_name"];?> <?= $user_data["last_name"];?>
                </div>
                <div class="user_info_alt">
                    <?= Features::getGender($user_data["gender"]);?>, <?= Date::dateToAge($user_data["birthday"]);?>
                </div>
                <div class="user_details">
                    <div>
                        <span>Phone:</span> <?= Format::phoneNumber($user_data["phone"]);?><br/>
                        <span>E-mail:</span> <?= $user_data["email"];?><br/>
                        <span>Address:</span> <?= $user_data["apt_address"]?>, <?= $user_data["room_name"]?> <?= $user_data["bed_name"]?>
                    </div>
                </div>
                <div style="margin:20px 0;">
                    <a target="_blank" class="default" href="<?=Url::to('user/main/profile')?>"><?=$lng->get('Edit Profile')?></a><br/>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="user_info_right">
                <div class="balance"> $<?= $user_data["balance"];?></div>
                <div><?=$lng->get('Balance')?></div>
                <div class="monthly_charges">$<?= $user_data["rent"];?></div>
                <div><?=$lng->get('Rent')?></div>

                <?php if($user_data["parking"]>0):?>
                <div class="monthly_charges">$<?= $user_data["parking"];?></div>
                <div><?=$lng->get('Parking')?></div>
                <?php endif;?>

                <div class="pay_now"><i class="fas fa-money-check-alt"></i> <a href="/user/payments/index"><?=$lng->get('Pay Now')?></a></div>
            </div>
        </div>
    </div>

    <div class="progress user_score">
        <div class="progress-bar progress-bar-success" role="progressbar"
             aria-valuenow="<?=$user_data['score']?>" style="width:<?=$user_data['score']?>%" aria-valuemin="0" aria-valuemax="100" >
            <?=$lng->get('Score')?>: <?=$user_data['score']?>
        </div>
    </div>
<!--        Notices-->
        <?php
        if($new_notices==1){
            ?><div class="header_notice"><i class="fa fa-exclamation-circle"></i> You have <?=$new_notices?> new Notice <a href="/user/notices/index">View</a></div> <?php
        }elseif($new_notices>1){
            ?><div class="header_notice"><i class="fa fa-exclamation-circle"></i> You have <?=$new_notices?> new Notices <a href="/user/notices/index">View</a></div> <?php
        }

        ?>
</section>



<!-- Main content -->
<section class="content">
    <div class="row">
    <?php foreach ($menu_list_index as $menu): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 mar-top-40">
            <div class="panelitembox" onclick="javascript:window.location.href = '<?= Url::to('user/'.$menu['url'])?>'">
                <div class="panelitemiconbox">
                    <div class="icon"><i class="fas fa-<?=$menu['icon']?>"></i></div>
                </div>

                <span class="panelitemyazi">
                    <?=$menu['name']?>
                    <?= ($menu['name']=='Notices' && $new_notices>0)?'<span style="color:red;font-weight: bold">('.$new_notices.')</span>':''?>
                </span>
            </div> <!-- item end -->
        </div>
    <?php endforeach; ?>
    </div>

</section>
