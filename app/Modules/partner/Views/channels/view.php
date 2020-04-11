<?php
use \Helpers\Csrf;
use Helpers\Date;
use Helpers\Format;
use Helpers\Features;
use Helpers\Url;
use Models\LanguagesModel;
$params = $data['params'];
$item = $data['item'];
$sms_list = $data['sms_list'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="row header_info_user">

        <div class="col-sm-3">
            <div class="user_image">
                <img src="<?= URL::getUserImage($item['id'],$item['gender'])?>" alt="">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="user_info_center">
                <div class="user_name">
                    <?= $item["first_name"];?> <?= $item["last_name"];?>
                </div>
                <div class="user_info_alt">
                    <?= Features::getGender($item["gender"]);?>, <?= Date::dateToAge($item["birthday"]);?>
                </div>
                <div class="user_details">
                    <div>
                        <span>Phone:</span> <?= Format::phoneNumber($item["phone"]);?><br/>
                        <span>E-mail:</span> <?= $item["email"];?><br/>
                        <span>Address:</span> <?= $item["apt_address"]?>, <?= $item["room_name"]?> <?= $item["bed_name"]?>
                    </div>
                </div>
                <div style="margin:20px 0;">
                    <a target="_blank" class="default" href="../view_portal/<?= $item["id"];?>"><?=$lng->get('View Portal')?></a><br/>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="user_info_right">
                <div class="balance"> $<?= $item["balance"];?></div>
                <div><?=$lng->get('Balance')?></div>
                <div class="monthly_charges">$<?= $item["rent"];?></div>
                <div><?=$lng->get('Monthly Charges')?></div>
            </div>
        </div>
    </div>

</section>

<section class="content">
    <div class="col-lg-10 col-md-12">

        <div class="row">


            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Tenant Status')?></div>
                    <div class="half_box_body">
                        <ul>
                            <li><span>Move In:</span> <?= $item["move_in"];?></li>
                            <li><span>Move Out:</span> <?= ($item["move_out"]=='')?'- -':$item["move_out"];?></li>
                            <li><span>Notice:</span> <?= ($item["notice_date"]=='')?'- -':$item["notice_date"];?></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Balance Logs')?></div>
                    <div class="half_box_body table-responsive">
                        <table class="default">
                            <tr>
                                <th><?=$lng->get('Date')?></th>
                                <th><?=$lng->get('Amount')?> (<?=DEFAULT_CURRENCY_SHORT?>)</th>
                                <th><?=$lng->get('Type')?></th>
                                <th><?=$lng->get('Description')?></th>
                                <th><?=$lng->get('Receipt')?></th>
                            </tr>
                            <?php foreach ($data['balance_logs'] as $data):?>
                            <tr>
                                <td><?=date('m/d/Y H:i',$data['time'])?></td>
                                <td><?=$data['amount']?></td>
                                <td><?=$lng->get($data['action'])?></td>
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

        <div class="half_box_with_title" id="sms_history">
            <div class="half_box_title"><?=$lng->get('SMS History')?></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="half_box_body custom_block default">
                        <?php if(count($sms_list)>0):?>
                            <?php foreach ($sms_list as $list) :?>
                                <?php if($item['phone']==$list['sms_to']):?>
                                    <div class="message_block message_block_mine">
                                        <?=Format::getText($list['text'])?>
                                        <span><?=Date::timeToDate($list['time'])?></span>
                                    </div>
                                    <div class="clearBoth"></div>
                                <?php else:?>

                                    <div class="message_block ">
                                        <?=Format::getText($list['text'])?>
                                        <span><?=Date::timeToDate($list['time'])?></span>
                                    </div>
                                <?php endif;?>
                            <?php endforeach;?>
                        <?php else:?>
                            <div style="font-size: 16px;padding:10px;"><?=$lng->get('There is no SMS history yet')?></div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="half_box_body custom_block default">
                        <form action="" method="POST">
                            <input type="hidden" value="<?=Csrf::makeToken('sms')?>" name="csrf_tokensms" />

                            <div class="form-group">
                                <textarea name="text" placeholder="<?=$lng->get('Start typing')?>"></textarea>
                            </div>
                            <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Send SMS')?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php include "right_panel.php"; ?>

</section><!-- /.content -->