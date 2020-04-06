<?php

use Helpers\Url;
use Helpers\Format;
use Helpers\Features;
use Helpers\Date;
use Models\UserModel;
use Models\MessagesModel;

?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row inner_background">
                <div class="col-sm-12">
                    <h1 class="page_title"><?=$data['page_title']?></h1>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="container">
                        <div class="row paddingTop20 paddingBottom40">

                            <div class="col-sm-12">
                                <div class="default filter_buttons">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <form action="" method="post">
                                                <?=$lng->get('Order')?>:
                                                <select name="list_order" style="width:140px"onchange="this.form.submit()">
                                                    <option value="last"<?=($data['list_order']=='last')?'selected':''?>><?=$lng->get('Recent')?></option>
                                                    <option value="high_price" <?=($data['list_order']=='high_price')?'selected':''?>><?=$lng->get('Oldest first')?></option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $list_count = count($data['list']);

                                if($list_count>0):
                                    foreach ($data['list'] as $list):
                                        if($list['id1']==$data['user_info']['id']){
                                            $user_info = UserModel::getInfo($list['id2']);
                                        }else{
                                            $user_info = UserModel::getInfo($list['id1']);
                                        }
                                        $new_message = MessagesModel::countNewMessagesChat($user_info['id']);
                                        if($new_message==0){
                                            $new_message = '';
                                        }else{
                                            $new_message = '<div class="new_message_inner">'.$new_message.' '.$lng->get('New').'</div>';
                                        }
                                        ?>
                                        <a href="message/<?=$user_info['id']?>" class="messages_box_a">
                                            <div class="messages_box" style="display: table;width:100%;">
                                                <div class="row">
                                                    <div class="col-sm-12 remove_col_padding_mob">
                                                        <div class="row">
                                                            <div class="col-md-3" style="display: table-cell;width:110px;">
                                                                <div class="message_img">
                                                                    <img src="<?= URL::getUserImage($user_info['id'])?>" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9 roommate_list_info">
                                                                <div class="roommate_list_title">
                                                                    <h3 style="float:left;">
                                                                        <?=Format::listTitle($user_info['first_name'])?>
                                                                    </h3>
                                                                    <?=$new_message?>
                                                                    <div class="clearBoth"></div>


                                                                    <div class="roommate_list_subtitle">

                                                                        <?=Features::getGender($user_info['gender'])?>
                                                                        <?=Date::dateToAge($user_info['birthday'])?> <?=$lng->get("years")?><br/>
                                                                        <?=$lng->get('Last active')?>: <?=Date::getOnline($user_info['time'])?>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    var range1 = document.getElementById("range1");
    var range2 = document.getElementById("range2");
    var spanRange1 = document.getElementById("spanRange1");
    var spanRange2 = document.getElementById("spanRange2");
    range1.onchange = function() {
        spanRange1.innerHTML = this.value+' <?=DEFAULT_CURRENCY;?>';
    }
    range2.onchange = function() {
        if(this.value=='2000'){
            spanRange2.innerHTML = this.value+'+ <?=DEFAULT_CURRENCY;?>';
        }else {
            spanRange2.innerHTML = this.value + ' <?=DEFAULT_CURRENCY;?>';
        }
    }
</script>
