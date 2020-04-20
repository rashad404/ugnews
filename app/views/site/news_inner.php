<?php
use Helpers\Url;
use Helpers\Format;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container paddingX">
            <div class="row paddingTop20 paddingBottom40">

                <div class="col-lg-8 news_inner_box">
                    <div class="">
                        <div class="row">
                            <div class="col-sx-12">

                                <div class="channel_info_inner remove_col_padding_mob" style="padding: 12px 10px;">
                                    <?php $channel_info = \Models\ChannelsModel::getItem($item['channel']);?>


                                    <div class="row">
                                        <div class="col-xs-2 col-lg-2 remove_col_padding_mob" style="text-align: right;width:12%;">
                                            <img class="channel_img" src="<?=Url::filePath()?>/<?=$channel_info['thumb']?>" alt=""/>
                                        </div>
                                        <div class="col-xs-6 col-lg-7 remove_col_padding_web" >
                                            <div class="news_box_channel_title">
                                                <a href="/<?=Format::urlTextChannel($channel_info['name_url'])?>"><?=$channel_info['name'];?></a>
                                            </div>
                                            <div class="channel_info_news_date"><?=date("H:i",$item['time'])?></div>
                                        </div>
                                        <div class="col-xs-2  col-lg-1">
                                            <div class="channel_info_inner_view">
                                                <?php $subscribe_count = \Models\ChannelsModel::countSubscribers($item['channel']);?>
                                                <span style=""><?=$subscribe_count?><br/><?=$lng->get('subscribers')?></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-lg-2">
                                            <div class="channel_info_inner_view" style="text-align: center">
                                                <?=$item['view']?><br/><i class="fas fa-signal"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="news_inner_title"><?=$item['title']?></div>

                        <div class="row">
                            <div class="col-sm-8 col-md-8 col-lg-8">
                                <?php if(!empty($item['image'])):?>
                                    <img class="news_inner_img" src="<?=Url::filePath()?>/<?=$item['image']?>" alt="" />
                                <?php else:?>
                                    <div class="news_inner_text">
                                        <?=html_entity_decode($item['text'])?>
                                    </div>
                                <?php endif;?>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 web_pl_remove">
                                <div class="news_inner_right_box">
                                    <i class="fas fa-tag"></i> <?=$lng->get(\Models\NewsModel::getCatName($item['cat']))?>
                                </div>

                                <?php
                                $subscribe_check = \Models\NewsModel::subscribeCheck($item['channel']);
                                $like_check = \Models\NewsModel::likeCheck($item['id']);
                                $dislike_check = \Models\NewsModel::dislikeCheck($item['id']);
                                ?>
                                <div class="news_inner_right_box" style="padding: 5px 10px;">
                                    <div class="news_inner_subscribe_area">
                                        <button redirect_url="news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" id="subscribe_button" channel_id="<?=$item['channel']?>" class="<?=($data['userId']>0)?'':'umodal_toggle'?> subscribe <?=($subscribe_check===true)?' subscribed':''?>">
                                            <i class="fas fa-<?=($subscribe_check===true)?'bell-slash':'bell'?>"></i>
                                            <span><?=$lng->get(($subscribe_check===true)?'Subscribed':'Subscribe')?></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="news_inner_right_box" style="padding: 5px 10px;">
                                    <div class="news_inner_subscribe_area">
                                        <button redirect_url="news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" id="like_button" news_id="<?=$item['id']?>" class="<?=($data['userId']>0)?'':'umodal_toggle'?> like <?=($like_check===true)?' liked':''?>">
                                            <i class="fas fa-<?=($like_check===true)?'thumbs-up':'thumbs-up'?>"></i>
                                        </button>
                                        <button redirect_url="news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" id="dislike_button" news_id="<?=$item['id']?>" class="<?=($data['userId']>0)?'':'umodal_toggle'?> dislike <?=($dislike_check===true)?' disliked':''?>">
                                            <i class="fas fa-<?=($dislike_check===true)?'thumbs-down':'thumbs-down'?>"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="news_inner_right_box">
                                    <div class="share_btns">

                                        <a href="https://www.facebook.com/sharer/sharer.php?u=https://ug.news/news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" class="fb_share" target="_blank">
                                            <i class="fab fa-facebook-f"></i> <span><?=$lng->get('Share')?></span>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?text=<?=Format::listTitle($item['title'])?>&url=https://ug.news/news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" class="tw_share" target="_blank">
                                            <i class="fab fa-twitter" aria-hidden="true"></i> <span>Tweet</span>
                                        </a>
                                        <a href="whatsapp://send?text=<?=Format::listTitle($item['title'])?> https://ug.news/news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" data-action="share/whatsapp/share" class="wtp_share">
                                            <i class="fab fa-whatsapp" aria-hidden="true"></i> <span>Whatsapp</span>
                                        </a>

                                        <a href="mailto:?subject=<?=Format::listTitle($item['title'])?> &body=https://ug.news/news/<?=$item['id']?>/<?=Format::urlText($item['title'])?>" class="em_share">
                                            <i class="fas fa-envelope" aria-hidden="true"></i> <span>E-mail</span>
                                        </a>
                                    </div>
                                    <div class="clearBoth"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($item['image'])):?>
                    <div class="news_inner_text">
                        <?=html_entity_decode($item['text'])?>
                    </div>
                    <?php endif;?>
                    <div class="news_inner_navigate" style="display: none">
                        <?php if($previous_item['id']>0):?><div class="news_inner_date"><a href="news/<?=$previous_item['id']?>/<?=Format::urlText($previous_item['title'])?>"><<< <?=$lng->get('Previous News')?></a></div><?php endif;?>
                        <?php if($next_item['id']>0):?><div class="news_inner_title"><a href="news/<?=$next_item['id']?>/<?=Format::urlText($next_item['title'])?>"><?=$lng->get('Next News')?> >>></a></div><?php endif;?>
                    </div>
                </div>




                <div class="col-lg-4 remove_col_padding">
                    <div class="similar_news">

                            <div class="similar_news_title"><?=$lng->get('Similar News')?>:</div>
                            <?php foreach ($data['list'] as $list):?>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 remove_col_padding">
                                    <div class="news_box_similar">
                                            <a href="news/<?=$list['id']?>/<?=Format::urlText($list['title'])?>">
                                                <div class="">
                                                    <?php $channel_info = \Models\ChannelsModel::getItem($list['channel']);?>

                                                    <div class="row">
                                                        <div class="col-xs-6 remove_col_padding_mob">
                                                            <img class="" src="<?=Url::filePath()?>/<?=$list['thumb']?>" alt="" />
                                                        </div>
                                                        <div class="col-xs-6 custom_padding_smilar_news">
                                                            <div class="news_box_similar_title"><?=Format::listTitle($list['title'], 50)?></div>
                                                            <div class="news_box_similar_title_channel_name">
                                                                <?=$channel_info['name']?>
                                                            </div>
                                                            <div class="news_box_similar_date"><?=$list['view']?> <?=$lng->get('view')?> <i class="fas fa-calendar"></i> <?=date("H:i",$list['time'])?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                </div>
                            <?php endforeach; ?>

                    </div>
                </div>

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
    </div>
</div>