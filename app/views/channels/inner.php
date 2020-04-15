<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
?>

<main class="main">
    <div class="container-fluid inner_background">
        <h3><?=$item['name']?></h3>
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
                                        <div class="row news_date">
                                            <div class="col-xs-8 col-md-8">
                                                <?=date("M d Y",$list['time'])?>
                                            </div>
                                            <div class="col-xs-4 col-md-4">
                                                <span style="float:right;"><?=date("H:i",$list['time'])?></span>
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
</main>
