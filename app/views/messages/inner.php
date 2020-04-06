<?php

use Helpers\Csrf;
use Helpers\Date;
use Helpers\Features;
use Helpers\Url;
use Helpers\Format;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><?=$lng->get("Message to")?> <?=$data['to_info']['first_name']?>
        </div>
        <div class="container">
            <div class="row paddingBottom40">
                <div class="col-md-1">
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumbs">
                                <a href="messages"><?=$lng->get("Messages")?></a> /
                                <?=$data['to_info']['first_name']?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom_block">

                                <div class="row">
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="profile_photo">
                                            <img src="<?= URL::getUserImage($data['to_info']['id']) ?>" title="">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-9">
                                        <div><label><?=$lng->get('Name')?>:</label> <?=$data['to_info']['first_name']?></div>
                                        <?php
                                            $birthday_exp = explode('-', $data['to_info']['birthday']);
                                            $birth_year = $birthday_exp[0];
                                        ?>
                                        <div><label><?=$lng->get('Age')?>:</label> <?=Date::yearToAge($birth_year)?></div>
                                        <div><label><?=$lng->get('Gender')?>:</label> <?=Features::getGender($data['to_info']['gender'])?></div>
                                        <div><label><?=$lng->get('Last active')?>:</label> <?=Date::getOnline($data['to_info']['time'])?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(count($list)>0):?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom_block default">
                                <?php foreach ($list as $item) :?>
                                    <?php if($data['user_info']['id']==$item['user_id']):?>
                                    <div class="message_block message_block_mine">
                                        <?=Format::getText($item['text'])?>
                                        <span><?=Date::timeToDate($item['time'])?></span>
                                    </div>
                                    <div class="clearBoth"></div>
                                    <?php else:?>

                                    <div class="message_block ">
                                        <?=$item['text']?>
                                        <span><?=Date::timeToDate($item['time'])?></span>
                                    </div>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom_block default">
                                <form action="" method="POST">
                                    <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />

                                        <div class="form-group">
                                            <label><?=$lng->get('Your text')?></label><br/>
                                            <textarea name="text" placeholder="<?=$lng->get('Start typing')?>"></textarea>
                                        </div>
                                    <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Send message')?></button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-2">
                </div>
            </div>
        </div>
    </section>
</main>
