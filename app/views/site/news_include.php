<?php
use Helpers\Url;
use Helpers\Format;
    foreach ($data['list'] as $list):?>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="news_box">

                <div class="channel_info">
                    <?php $channel_info = \Models\ChannelsModel::getItem($list['channel']);?>


                    <div class="row">
                        <div class="col-xs-2 remove_col_padding_mob">
                            <img class="channel_img" src="<?=Url::filePath()?><?=$channel_info['thumb']?>" alt=""/>
                        </div>
                        <div class="col-xs-7">
                            <div class="news_box_channel_title">
                                <a href="/<?=Format::urlTextChannel($channel_info['name_url'])?>"><?=$channel_info['name'];?></a>
                            </div>
                            <?php
                                if(date("d",$list['publish_time'])==date('d')){
                                    $news_date = date("H:i",$list['publish_time']);
                                }else{
                                    $news_date = date("d.m.Y",$list['publish_time']);
                                }
                            ?>
                            <div class="news_box_date"><?=$news_date?></div>
                        </div>
                        <div class="col-xs-3">
                            <div class="news_box_view">
                                <?=$list['view']?><br/><i class="fas fa-signal"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="news/<?=$list['id']?>/<?=Format::urlText($list['title'])?>">

                    <?php if(!empty($list['thumb'])):?>
                        <img class="news_image" src="<?=Url::filePath()?><?=$list['thumb']?>" alt="" />

                        <div class="caption">
                            <div class="news_title">
                            <span>
                                <?=Format::listTitle($list['title'], 70)?> <span style="color:red;"><?=Format::listTitle($list['title_extra'], 70)?></span>
                            </span>
                            </div>
                        </div>
                        <div class="news_cat">
                            <?=$lng->get(\Models\NewsModel::getCatName($list['cat']))?>
                        </div>
                    <?php else:?>
                        <div class="news_only_text">
                            <?=Format::listText($list['text'], 300)?>... <span><?=$lng->get('Read more')?></span>
                        </div>
                    <?php endif;?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="clearBoth"></div>
    <div style="text-align:center;">
        <?php echo $data["pagination"]->pageNavigation('pagination')?>
    </div>
