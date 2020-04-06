<?php

use Helpers\Csrf;
use Helpers\Date;
use Models\LanguagesModel;
use Helpers\Format;
use Helpers\Features;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\CustomersModel;
$params = $data['params'];
$item = $data['item'];
$sms_list = $data['sms_list'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="header_info">
        <a href="../index"><?=$params['title']?></a> / <span style="font-weight: bold"><?= $item["first_name"];?> <?= $item["last_name"];?></span><br/>
    </div>
    <div>

    </div>
</section>

<section class="content">
    <div class="col-lg-10 col-md-12">

        <div class="row">


            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Personal Info')?></div>
                    <div class="half_box_body">

                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('First Name')?>:</td>
                                <td><?= $item["first_name"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Last Name')?>:</td>
                                <td><?= $item["last_name"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Phone')?>:</td>
                                <td><?= Format::phoneNumber($item["phone"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('E-mail')?>:</td>
                                <td><?= $item["email"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Gender')?>:</td>
                                <td><?= Features::getGender($item["gender"])?></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Looking for')?></div>
                    <div class="half_box_body">
                        <ul>
                            <table class="default_vertical">
                                <tr>
                                    <td><?=$lng->get('Location')?>:</td>
                                    <td><?= ApartmentsModel::getLocationName($item["location"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Apartment')?>:</td>
                                    <td><?= ApartmentsModel::getName($item["apt_id"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Room type')?>:</td>
                                    <td><?= RoomsModel::getName($item["room_type"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Apt Model')?>:</td>
                                    <td><?= ApartmentsModel::getModelName($item["apt_model"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Budget')?>:</td>
                                    <td><?= DEFAULT_CURRENCY_SHORT.$item["budget"]?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Desired Move in')?>:</td>
                                    <td><?=$item["desired_move_in"]?></td>
                                </tr>
                            </table>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Additional Info')?></div>
                    <div class="half_box_body">

                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('Source')?>:</td>
                                <td><?= CustomersModel::getSourceName($item["source"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Notes')?>:</td>
                                <td><?= $item["note"]?></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-sm-12">
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
                                            <?=$list['text']?>
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

        </div>


    </div>

</section><!-- /.content -->