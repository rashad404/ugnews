<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">

<!--    Why Us?-->
    <div class="container paddingBottom20">
            <div class="row paddingBottom40">
                <div class="col-sm-12">


                    <div class="row">

                        <div class="col-lg-12">
                            <div class="page_title paddingTop20 paddingBottom20">
                                <h2>
                                    <?=$lng->get($data['cat_name']).$lng->get(' News')?>
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
