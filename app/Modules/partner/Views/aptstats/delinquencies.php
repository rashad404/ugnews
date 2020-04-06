<?php

use Helpers\Format;
use Models\LanguagesModel;
use Modules\partner\Models\AptStatsModel;
use \Helpers\Session;
$user_role = Session::get('partner_session_role');

$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();


$sum_total = AptStatsModel::sumDelinquencies();
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
            <div class="col-sm-12 header_stats">
                <?=$lng->get('Total')?>:
                <span>$<?=$sum_total?></span>
            </div>
            <?php endif;?>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_body table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th style="width: 10px;padding: 10px!important;">#</th>
                                <th><?=$lng->get('Tenant')?></th>
                                <th><?=$lng->get('Balance')?> (<?=DEFAULT_CURRENCY_SHORT?>)</th>
                            </tr>
                            <?php $c=1; foreach ($data['list'] as $data):?>
                                <?php $note = Format::listText($data["note"],255); ?>
                                <tr>
                                    <td><?=$c?>.</td>
                                    <td>
                                        <a target="_blank" href="../tenants/view/<?= $data["id"]?>"><?= $data["first_name"]?> <?= $data["last_name"]?></a><br/>
                                        <div class="list_alt_text">
                                            <i class="fa fa-phone"></i> <span style="color:#496086;cursor:pointer;" onclick="copyFunction()"><?=Format::phoneNumber($data['phone'])?></span> <i class="fa fa-envelope"></i> <?= $data["email"]?>
                                        </div>
                                        <?php if(strlen($note)>0):?>
                                        <div><span style="font-weight: bold;"><?=$lng->get('Note')?>:</span> <?= Format::listText($data["note"],255) ?></div>
                                        <?php else:?>
                                            <div><span style="font-weight: bold;"><a target="_blank" href="../tenants/update/<?= $data["id"]?>"><?=$lng->get('Add note')?></a></div>

                                        <?php endif;?>
                                    </td>
                                    <td><?=$data['balance']?></td>
                                </tr>
                            <?php $c++; endforeach;?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section><!-- /.content -->


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
