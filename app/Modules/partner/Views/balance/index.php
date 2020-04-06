<?php

use Helpers\Csrf;
use Models\LanguagesModel;
use Helpers\Format;
$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">


    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="half_box_with_title">
                        <div class="half_box_body table-responsive">
                            <table class="default">
                                <tr>
                                    <th><?=$lng->get('Date')?></th>
                                    <th><?=$lng->get('Tenant')?></th>
                                    <th><?=$lng->get('Amount')?> (<?=DEFAULT_CURRENCY_SHORT?>)</th>
                                    <th><?=$lng->get('Type')?></th>
                                    <th><?=$lng->get('Description')?></th>
                                    <th><?=$lng->get('Receipt')?></th>
                                </tr>
                                <?php foreach ($data['balance_logs'] as $data):?>
                                    <tr>
                                        <td><?=date('m/d/Y H:i',$data['time'])?></td>
                                        <td><a href="/partner/tenants/view/<?=$data['user_id']?>" target="_blank"> <?=$data['first_name']?> <?=$data['last_name']?></a></td>
                                        <td><?=$data['amount']?></td>
                                        <td><?=$lng->get($data['action'])?></td>
                                        <td><?=Format::getText($data['description'],'20')?></td>
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
        </div><!-- /.col -->

    </div><!-- /.row -->
</section><!-- /.content -->
