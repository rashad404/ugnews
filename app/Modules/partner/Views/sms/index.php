<?php

use Helpers\Date;
use Helpers\Features;
use Helpers\Session;
use \Helpers\Url;
use Helpers\Format;
use Models\LanguagesModel;
use Modules\partner\Models\SmsModel;
use Modules\partner\Models\UsersModel;
use Modules\partner\Models\CustomersModel;

$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
$partner_id = Session::get('user_session_id');
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">


    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6">

                <div class="default">
                    <form action="" method="post">
                        <?=$lng->get('Order')?>:
                        <select name="list_order" style="width:140px" onchange="this.form.submit()">
                            <option value="recent"<?=($data['list_order']=='recent')?'selected':''?>><?=$lng->get('Recent')?></option>
                            <option value="oldest" <?=($data['list_order']=='oldest')?'selected':''?>><?=$lng->get('Oldest first')?></option>
                        </select>
                    </form>
                </div>
                <br/>
            </div>
            <div class="col-xs-6" style="text-align: right">

            </div>
            <div class="col-xs-12">
                <a href="send" class="btn btn-success"><?=$lng->get('Send')?></a> <span style="font-weight: bold">OR</span> <a href="bulksend" class="btn btn-warning"><?=$lng->get('BULK Send')?></a><br/><br/>
            </div>
            <div class="col-md-12">
                <?php
                $list_count = count($data['list']);

                if($list_count>0):
                    foreach ($data['list'] as $list):
                        if($list['user_type']==1) {
                            $user_info = CustomersModel::getItem($list['user_id']);
                        }else{
                            $user_info = UsersModel::getItem($list['user_id']);
                        }
                        $new_message = SmsModel::countNewMessagesChat($user_info['id']);
                        if($new_message==0){
                            $new_message = '';
                        }else{
                            $new_message = '<div class="new_message_inner">'.$new_message.' '.$lng->get('New').'</div>';
                        }
                        ?>
                            <?php if($list['user_type']==1):?>
                                <a href="../customers/view/<?=$user_info['id']?>#sms_history" class="messages_box_a">
                            <?php else:?>
                                <a href="../tenants/view/<?=$user_info['id']?>#sms_history" class="messages_box_a">
                            <?php endif;?>
                            <div class="messages_box" style="display: table;width:100%;">
                                <div class="row">
                                    <div class="col-sm-12 remove_col_padding_mob">
                                        <div class="row">
                                            <div class="col-md-3" style="display: table-cell;width:80px;">
                                                <div class="message_img">
                                                    <img src="<?= URL::getUserImage($user_info['id'],$user_info['gender'])?>" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-9 roommate_list_info">
                                                <div class="roommate_list_title">
                                                    <h4 style="font-weight:bold;float:left;margin-top: 0!important;margin-bottom: 3px!important;">
                                                        <?=Format::listTitle($user_info['first_name'])?>
                                                    </h4>
                                                    <?=$new_message?>
                                                    <div class="clearBoth"></div>


                                                    <div style="font-size: 11px;font-style: italic;padding-bottom: 5px;">

                                                        <?=Format::phoneNumber($user_info['phone'])?><br/>
                                                    </div>
                                                </div>
                                                <div class="roommmate_list_area">
                                                    <div>
                                                        <?=Format::listTitle($list['last_text'])?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearBoth"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div class="full_occupancy">
                    <?=$lng->get('You don\'t have any messages yet')?>
                    </div><?php
                endif;
                ?>


            </div>
        </div>
    </div>
</section><!-- /.content -->
