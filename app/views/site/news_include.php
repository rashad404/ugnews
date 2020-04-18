<?php
use Helpers\Url;
use Helpers\Format;
    foreach ($data['list'] as $list):?>
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