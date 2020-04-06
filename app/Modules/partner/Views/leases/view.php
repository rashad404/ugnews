<?php
use \Helpers\Csrf;
use Helpers\Format;
use Models\LanguagesModel;
use Modules\partner\Models\LeasesModel;
use Helpers\Date;

$params = $data['params'];
$item = $data['item'];
$page = $data['page'];
$app_info = $data['app_info'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();

$start_date_value = date('m/d/Y');
$end_date_value = date('m/d/Y', time()+365*86400);

if($item['step']==1){
    $start_date_value =  Date::toInputFormat($item['start_date']);
    $end_date_value =  Date::toInputFormat($item['end_date']);
    $bed_id = $item['bed_id'];
    $rent = $item['rent'];
    $prorated_rent = $item['prorated_rent'];
}else if(!empty($app_info)){
    $start_date_value = $app_info['movein_date'];
    $movein_str = strtotime($app_info['movein_date']);

    $start_date_value = date("m/d/Y", $movein_str);
    $end_date_value = date('m/d/Y', $movein_str+365*86400);
    $bed_id = $app_info['bed_id'];
    $rent = $app_info['price'];

    $days_of_month = date("t", $movein_str);
    $movein_day = date("d", $movein_str);
    $prorated_day = $days_of_month - $movein_day+1;
    $prorated_rent = number_format($rent/30 * $prorated_day, 2,'.','');


}else{
    $start_date_value = date('m/d/Y');
    $end_date_value = date('m/d/Y', time()+365*86400);
    $bed_id = 0;
    $rent = 0;
    $prorated_rent = 0;
}
?>
<?php if($item['user_sign']==0):?>
<section class="content-header">
    <div class="header_info">
        <a href="/partner/leases/index"><?=$params['title']?></a> / <span style="font-weight: bold"><?= $item["user_first_name"];?> <?= $item["user_last_name"];?></span><br/>

        <form action="" method="post">
            <input type="hidden" value="<?=Csrf::makeToken();?>" name="csrf_token">
            <div class="form_box">
            <div class="row default">
                <div class="col-md-3">
                    <label><strong><?=$lng->get('Lease Start')?>:</strong></label><br/>
                    <div class="form-group default_date">
                        <div class="input-group date" id="datepicker1">
                            <input value="<?=$start_date_value?>" name="start_date" type="text">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label><strong><?=$lng->get('Lease End')?>:</strong></label><br/>
                    <div class="form-group default_date">
                        <div class="input-group date" id="datepicker2">
                            <input value="<?=$end_date_value?>" name="end_date" type="text">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label><strong><?=$lng->get('Select Room/Bed')?>:</strong></label><br/>
                    <select name="bed_id" class="select2 form-control">
                        <?php foreach($data['bed_list'] as $bed_list):?>
                        <option <?=$bed_id==$bed_list['key']?'selected':''?> value="<?=$bed_list['key']?>" <?=$bed_list['disabled']?>><?=$bed_list['name']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

            <div class="row default">
                <div class="col-md-3">
                    <label><strong><?=$lng->get('Monthly Rent')?> (<?=DEFAULT_CURRENCY_SHORT?>):</strong></label><br/>
                    <input class="form-control" type="text" placeholder="" name="rent" value="<?=$rent?>">
                </div>
                <div class="col-md-3">
                    <label><strong><?=$lng->get('Security Deposit')?> (<?=DEFAULT_CURRENCY_SHORT?>):</strong></label><br/>
                    <input class="form-control" type="text" placeholder="" name="deposit" value="<?=number_format(DEPOSIT_FEE, 2,'.','')?>">
                </div>
                <div class="col-md-3">
                    <label><strong><?=$lng->get('Application Fee')?> (<?=DEFAULT_CURRENCY_SHORT?>):</strong></label><br/>
                    <input class="form-control" type="text" placeholder="" name="app_fee" value="<?=number_format(APPLICATION_FEE,2,'.','')?>">
                </div>
            </div>

            <div class="row default">
                <div class="col-md-3">
                    <label><strong><?=$lng->get('Prorated Rent')?> (<?=DEFAULT_CURRENCY_SHORT?>):</strong></label><br/>
                    <input class="form-control" type="text" placeholder="" name="prorated_rent" value="<?=$prorated_rent?>">
                </div>
                <div class="col-md-3" style="">
                    <div style="margin-top: 26px;">
                        <button class="btn btn-success"><?=$lng->get('Prepare')?></button>
                    </div>
                </div>
            </div>

        </div>
        </form>
    </div>
</section>
<?php endif;?>

<?php if($item['step']==1): ?>
<section class="content">
    <div class="col-lg-8 col-md-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title_lease">
                        <?=$page['title'];?>
                        <div  class="lease_download">
                            <form action="" method="post">
                                <input type="hidden" value="<?= Csrf::makeToken('send_lease');?>" name="csrf_tokensend_lease">
                                <button class="btn btn-warning"><i class="fas fa-envelope"></i> <?=$lng->get('Send to Tenant')?></button>
                            </form>
                        </div>
                    </div>
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
                                        <div class="lease_signed_final"> X <span class="lease_signed_final_span">&nbsp;&nbsp;&nbsp;<?= LeasesModel::getSign($item['id'])?>&nbsp;&nbsp;&nbsp;</span></div>
                                        <div class="lease_signed_name" style="float: left"><?=$lng->get('Lessee')?></div>
                                        <div class="lease_signed_name" style="float: right;"><?=$lng->get('IP Address')?>: <?=$item['user_ip']?><br/>
                                            <?=date('m/d/Y H:i:s',$item['user_sign_time'])?>
                                        </div>
                                        <div class="clearBoth"></div>
                                    </div>
                                </div>
                            <?php else:?>
                                <div class="lease_sign_box">
                                    <input type="hidden" value="<?= \Helpers\Csrf::makeToken('final');?>" name="csrf_tokenfinal">
                                    <div class="lease_sign_content">
                                        <div class="lease_sign_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                        <div class="lease_sign"> X _________</div>
                                        <div class="lease_sign_name"><?=$lng->get('Sign Here')?> (<span><?=$lng->get('Click to add your signature')?></span>)</div>
                                    </div>
                                </div>
                            <?php endif;?>

                            <div class="lease_sign_box_landlord">
                                <div class="lease_sign_content">
                                    <div class="lease_sign"> X _________</div>
                                    <div class="lease_sign_name"><span><?=$lng->get('Lessor')?></span></div>
                                </div>
                            </div>

                        <?php else:?>
                            <?php if($item['user_sign']==1):?>
                                <div class="lease_signed_box">
                                    <div class="lease_signed_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                    <div class="lease_signed"> X <span>&nbsp;&nbsp;&nbsp;<?=LeasesModel::getInitials($item['id'])?>&nbsp;&nbsp;&nbsp;</span></div>
                                    <div class="lease_signed_name"><?=$item['user_first_name'].' '.$item['user_middle_name'].' '.$item['user_last_name']?></div>
                                </div>
                            <?php else:?>
                                <div class="lease_sign_box">
                                    <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                                    <div class="lease_sign_content">
                                        <div class="lease_sign_notice"><?=$lng->get('By initialing below, you acknowledge and agree to the terms in Section')?> <?=$page['title']?></div>
                                        <div class="lease_sign"> X _________</div>
                                        <div class="lease_sign_name"><?=$lng->get('Sign Here')?> (<span><?=$lng->get('Click to add your signature')?></span>)</div>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endif;?>
                        



                    </div>
                    <div class="pagination_pn">
                        <?php if($data['previous_page']>0):?>
                            <a href="/partner/leases/view/<?=$item["user_id"]?>/<?=$data["previous_page"]?>">&#8592; <?=$lng->get('Previous Page')?></a>
                        <?php endif;?>
                        <?php if($data['next_page']>0):?>
                            <a href="/partner/leases/view/<?=$item["user_id"]?>/<?=$data["next_page"]?>" style="float: right"><?=$lng->get('Next Page')?> &#8594;</a>
                        <?php endif;?>
                        <div class="clearBoth"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include "right_panel.php"; ?>
</section><!-- /.content -->
<?php endif;?>

<link rel="stylesheet" href="<?=\Helpers\Url::templateModulePath()?>css/select2.min.css" />
<script src="<?=\Helpers\Url::templateModulePath()?>js/select2.min.js"></script>
<script>
    $(".select2.form-control").select2( {
        placeholder: "---",
        allowClear: true
    } );
</script>

<script>
    $(document).ready(function() {
        $(function() {
            <?php for ($i=1;$i<3;$i++):?>
            $('#datepicker<?=$i?>').datetimepicker({
                format: 'L'
            });
            <?php endfor;?>

        });
    });
</script>