<?php
use Helpers\Format;
use Modules\user\Models\LeasesModel;
use Helpers\Url;
$params = $data['params'];
$item = $data['item'];
$lng = $data['lng'];

$path = Url::uploadPath().'partner_logos/'.$item['partner_id'].'.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$img_data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($img_data);

?>
<div style="text-align: center">
    <img class="lease_logo" src="<?=$base64?>" alt="logo"/>
</div>

<div class="lease_header_name"><?=$item['partner_first_name']?> <?=$item['partner_last_name']?></div>
<div class="lease_header_info"><?=$item['partner_address']?></div>
<div class="lease_header_info"><?=Format::phoneNumber($item['partner_phone'])?></div>
<section class="content">
    <div class="row">
        <div class="col-sm-12">
                    <?php $c=0;foreach ($data['lease_pages'] as $page):?>
                    <div class="half_box_with_title">
                        <div class="half_box_title_lease"><?=$page['title'];?></div>
                        <div class="half_box_body_lease">
                            <?php
                            $text = Format::getText($page['text'],100000);
                            $text = LeasesModel::replaceVariables($text, $item['id']);
                            ?>
                            <?=$text?>
                            <?php $next_page = LeasesModel::getNextPage($page['id']);if($next_page>0):?>
                            <div class="lease_signed_box">
                                <div class="lease_signed_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                <div class="lease_signed"> X <span>&nbsp;&nbsp;&nbsp;<?=LeasesModel::getInitials($item['id'])?>&nbsp;&nbsp;&nbsp;</span></div>
                                <div class="lease_signed_name"><?=$item['user_first_name'].' '.$item['user_middle_name'].' '.$item['user_last_name']?></div>
                            </div>
                            <?php
//                                $c++;if($c==0);break;
                            endif;?>
                        </div>
                    </div>
                    <?php endforeach;?>

                    <div class="lease_signed_box">
                        <div class="lease_signed_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                        <div style="display: inline-block;">
                            <div class="lease_signed_final"> X <span>&nbsp;&nbsp;&nbsp;<?=LeasesModel::getSign($item['id'])?>&nbsp;&nbsp;&nbsp;</span></div>
                            <div class="lease_signed_name" style="float: left"><?=$lng->get('Lessee')?></div>
                            <div class="lease_signed_name" style="float: right;"><?=$lng->get('IP Address')?>: <?=$item['user_ip']?><br/>
                                <?=date('m/d/Y H:i:s',$item['user_sign_time'])?>
                            </div>
                            <div class="clearBoth"></div>
                        </div>
                    </div>

                    <div class="lease_sign_box_landlord">
                        <div class="lease_sign_content">
                            <div class="lease_sign"> X _________</div>
                            <div class="lease_sign_name"><span><?=$lng->get('Lessor')?></span></div>
                        </div>
                    </div>
                </div>
    </div>
</section><!-- /.content -->