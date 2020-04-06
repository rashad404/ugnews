<?php
use \Helpers\Csrf;
use Helpers\Format;
use Models\LanguagesModel;
use Modules\user\Models\LeasesModel;
use Helpers\Date;

$params = $data['params'];
$item = $data['item'];
$page = $data['page'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();

$start_date_value = date('m/d/Y');
$end_date_value = date('m/d/Y', time()+365*86400);

$start_date_value =  Date::toInputFormat($item['start_date']);
$end_date_value =  Date::toInputFormat($item['end_date']);
$bed_id = $item['bed_id'];
$rent = $item['rent'];
$prorated_rent = $item['prorated_rent'];
?>

<section class="content-header">
    <div class="header_info">
        <a href="/user/leases/index"><?=$params['title']?></a> / <span style="font-weight: bold"><?= $item["user_first_name"];?> <?= $item["user_last_name"];?></span><br/>

    </div>
</section>

<?php if($item['step']==1): ?>
<section class="content">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-sm-12">
                    <?php if($item['user_sign']==1):?>
                        <div class="">
                            <div class="lease_signed_notice_header">
                                <i class="fas fa-check"></i> <?=$lng->get('Lease is ready.')?>

                                <div  class="lease_download">
                                    <i class="fas fa-eye"></i> <a target="_blank" href="/user/leases/view_lease/<?=$item['id']?>"><?=$lng->get('View')?></a> &nbsp;
                                    <i class="fas fa-download"></i> <a href="/user/leases/download_lease/<?=$item['id']?>"><?=$lng->get('Download')?></a>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                    <div class="half_box_with_title">
                        <div class="half_box_title_lease"><?=$page['title'];?></div>
                        <div class="half_box_body_lease">
                            <?php
                            $text = Format::getText($page['text'],100000);
                            $text = LeasesModel::replaceVariables($text, $item['id']);
                            ?>
                            <?=$text?>

                            <?php if($data['next_page']==0):?>

                                <?php if($item['user_sign']==1):?>
                                    <div class="lease_signed_box">
                                        <div class="lease_signed_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                        <div style="display: inline-block;">
                                            <div class="lease_signed_final"> X <span class="lease_signed_final_span">&nbsp;&nbsp;&nbsp;<?=LeasesModel::getSign($item['id'])?>&nbsp;&nbsp;&nbsp;</span></div>
                                            <div class="lease_signed_name" style="float: left"><?=$lng->get('Lessee')?></div>
                                            <div class="lease_signed_name" style="float: right;"><?=$lng->get('IP Address')?>: <?=$item['user_ip']?><br/>
                                                <?=date('m/d/Y H:i:s',$item['user_sign_time'])?>
                                            </div>
                                            <div class="clearBoth"></div>
                                        </div>
                                    </div>
                                <?php else:?>
                                <div class="lease_sign_box">
                                <form action="" method="POST" id="sign-form">
                                    <a href="#" onclick="document.getElementById('sign-form').submit();">
                                        <input type="hidden" value="<?= \Helpers\Csrf::makeToken('final');?>" name="csrf_tokenfinal">
                                        <div class="lease_sign_content">
                                            <div class="lease_sign_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                            <div class="lease_sign"> X _________</div>
                                            <div class="lease_sign_name"><?=$lng->get('Sign Here')?> (<span><?=$lng->get('Click to add your signature')?></span>)</div>
                                        </div>
                                    </a>
                                </form>
                                </div>
                                <?php endif;?>
                                <div class="lease_sign_box_landlord">
                                    <div class="lease_sign_content">
                                        <div class="lease_sign"> X _________</div>
                                        <div class="lease_sign_name"><span><?=$lng->get('Lessor')?></span></div>
                                    </div>
                                </div>

                            <?php else:?>
                                <?php if($page['user_sign']==1):?>
                                    <div class="lease_signed_box">
                                        <div class="lease_signed_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                        <div class="lease_signed"> X <span>&nbsp;&nbsp;&nbsp;<?=LeasesModel::getInitials($item['id'])?>&nbsp;&nbsp;&nbsp;</span></div>
                                        <div class="lease_signed_name"><?=$item['user_first_name'].' '.$item['user_middle_name'].' '.$item['user_last_name']?></div>
                                    </div>
                                <?php else:?>
                                    <div class="lease_sign_box">
                                        <form action="" method="POST" id="sign-form">
                                            <a href="#" onclick="document.getElementById('sign-form').submit();">
                                                <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                                                <div class="lease_sign_content">
                                                    <div class="lease_sign_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                                    <div class="lease_sign"> X _________</div>
                                                    <div class="lease_sign_name"><?=$lng->get('Sign Here')?> (<span><?=$lng->get('Click to add your signature')?></span>)</div>
                                                </div>
                                            </a>
                                        </form>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                        </div>

                        <div class="pagination_pn">
                            <?php if($data['previous_page']>0):?>
                                <a href="/user/leases/view/<?=$item["id"]?>/<?=$data["previous_page"]?>">&#8592; <?=$lng->get('Previous Page')?></a>
                            <?php endif;?>
                            <?php if($data['next_page']>0):?>
                                <a href="/user/leases/view/<?=$item["id"]?>/<?=$data["next_page"]?>" style="float: right"><?=$lng->get('Next Page')?> &#8594;</a>
                            <?php endif;?>
                            <div class="clearBoth"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php include "right_panel.php"; ?>
    </div>
</section><!-- /.content -->
<?php endif;?>