<?php
use Helpers\Url;
use Helpers\Format;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid paddingX">
            <div class="row paddingTop20 paddingBottom40">
                <div class="col-lg-1"></div>
                <div class="col-lg-7 news_inner_box">
                    <div class="">
                        <div class="news_inner_title"><?=$item['title']?></div>

                        <div class="row">
                            <div class="col-sm-8 col-md-8 col-lg-8">
                                <img class="news_inner_img" src="<?=Url::filePath()?>/<?=$item['image']?>" alt="" />
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 web_pl_remove">
                                <div class="news_inner_right_box">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <i class="fas fa-clock"></i> <?=date("H:i",$item['time'])?>

                                        </div>
                                        <div class="col-xs-6">
                                            <div style="text-align: center">
                                                <i class="fas fa-calendar"></i> <?=date("M d Y",$item['time'])?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="news_inner_right_box">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <i class="fas fa-eye"></i> <?=$item['view']?> <?=$lng->get('view')?>

                                        </div>
                                        <div class="col-xs-6">
                                            <div style="text-align: center">
                                            <i class="fas fa-bell"></i>
                                                <span style=""><?=$item['view']?> <?=$lng->get('subscribers')?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="news_inner_right_box">
                                    <i class="fas fa-tag"></i> <?=$lng->get(\Models\NewsModel::getCatName($item['cat']))?>
                                </div>
                                <div class="news_inner_right_box">
                                    <i class="fas fa-at"></i> <span style="font-size: 18px;"><?=\Models\NewsModel::getChannelName($item['channel'])?></span>
                                </div>
                                <div class="news_inner_right_box">

                                    <?php
                                        $subscribe_check = \Models\NewsModel::subscribeCheck($item['channel']);
                                    ?>
                                    <button redirect_url="news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" id="subscribe_button" channel_id="<?=$item['channel']?>" class="<?=($data['userId']>0)?'':'umodal_toggle'?> subscribe <?=($subscribe_check===true)?' subscribed':''?>">
                                        <i class="fas fa-<?=($subscribe_check===true)?'bell-slash':'bell'?>"></i>
                                        <span><?=$lng->get(($subscribe_check===true)?'Subscribed':'Subscribe')?></span>
                                    </button>
                                    <button class="thumbs_up"><i class="fas fa-thumbs-up"></i></button>
                                    <button class="thumbs_down"><i class="fas fa-thumbs-down"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="news_inner_text">
                        <?=html_entity_decode($item['text'])?>
                    </div>
                    <div class="news_inner_navigate" style="display: none">
                        <?php if($previous_item['id']>0):?><div class="news_inner_date"><a href="news/<?=$previous_item['id']?>/<?=Format::urlText($previous_item['title'])?>"><<< <?=$lng->get('Previous News')?></a></div><?php endif;?>
                        <?php if($next_item['id']>0):?><div class="news_inner_title"><a href="news/<?=$next_item['id']?>/<?=Format::urlText($next_item['title'])?>"><?=$lng->get('Next News')?> >>></a></div><?php endif;?>
                    </div>
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>

    </section>
</main>


<div class="umodal login">
    <div class="umodal_box">
        <div class="umodal_head">
            <div class="umodal_title"><h2 class="title" id="umodal_title"><?=$lng->get('Login')?></h2></div>
            <div class="umodal_close"><i class="fas fa-times"></i></div>
            <div class="clearBoth"></div>
            <hr class="dark_gray"/>
        </div>
        <div class="umodal_body">
            <?php require $data['modal_url'];?>
        </div>
        <!--        <div id="redirect_url">-->
        <!---->
        <!--        </div>-->
    </div>
</div>