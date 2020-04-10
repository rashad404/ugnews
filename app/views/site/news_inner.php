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
                            <div class="col-lg-8">
                                <img class="news_inner_img" src="<?=Url::filePath()?>/<?=$item['image']?>" alt="" />
                            </div>
                            <div class="col-lg-4 web_pl_remove">
                                <div class="news_inner_right_box">
                                    <i class="fas fa-clock"></i>
                                    <?=date("H:i",$item['time'])?>
                                    <?=date("M d Y",$item['time'])?>
                                </div>
                                <div class="news_inner_right_box">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <i class="fas fa-at"></i> <span style="font-size: 18px;"><?=\Models\UserModel::getName($item['partner_id'])?></span>
                                        </div>
                                        <div class="col-xs-6">
                                            <div style="padding-top: 3px;text-align: center">
                                            <i class="fas fa-bell"></i>
                                                <span style="font-size: 14px;"><?=$item['view']?> <?=$lng->get('subscribers')?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="news_inner_right_box">
                                    <i class="fas fa-tag"></i> <?=$lng->get(\Models\NewsModel::getCatName($item['cat']))?>
                                </div>
                                <div class="news_inner_right_box">
                                    <i class="fas fa-eye"></i> <?=$item['view']?> <?=$lng->get('view')?>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div style="clear: both"></div>
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
