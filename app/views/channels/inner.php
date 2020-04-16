<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
$subscribe_check = \Models\NewsModel::subscribeCheck($item['id']);
$subscriber_count = \Models\ChannelsModel::countSubscribers($item['id']);

?>

<main class="main">
    <div class="container-fluid channel_title">
        <img src="<?=Url::uploadPath().$item['thumb']?>?a" alt=""><br/>
        <span class="title"><?=$item['name']?></span><br/>
        <span class="subscribe_url">https://ug.news/<?=strtolower($item['name_url'])?></span><br/>
        <span class="subscribe_count"><?=$subscriber_count?> <?=$lng->get('subscribers')?></span>

        <div class="channel_title_subscribe">
            <button redirect_url="<?=Format::urlText($item['name_url'])?>" id="subscribe_button" channel_id="<?=$item['id']?>" class="<?=($data['userId']>0)?'':'umodal_toggle'?> subscribe <?=($subscribe_check===true)?' subscribed':''?>">
                <i class="fas fa-<?=($subscribe_check===true)?'bell-slash':'bell'?>"></i>
                <span><?=$lng->get(($subscribe_check===true)?'Subscribed':'Subscribe')?></span>
            </button>
        </div>
    </div>
    <div class="container paddingBottom20">
        <div class="row paddingBottom40">
            <div class="col-sm-12">

                <div class="row">

                    <div class="col-lg-12">
                        <div class="page_title paddingBottom20">
                            <h2>
                                <?=$lng->get('Latest News')?>
                            </h2>
                            <hr/>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <?php foreach ($data['list'] as $list):?>
                        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <div class="news_box">
                                <a href="news/<?=$list['id']?>/<?=Format::urlText($list['title'])?>">
                                    <img class="news_image" src="<?=Url::filePath()?>/<?=$list['image']?>" alt="" />
                                    <div class="caption">
                                        <div class="news_title">
                                                <span>
                                                    <?=Format::listTitle($list['title'], 50)?>
                                                </span>
                                        </div>
                                    </div>
                                </a>
                                <div class="news_date">
                                    <?=date("H:i",$list['time'])?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
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
    </div>
</div>
