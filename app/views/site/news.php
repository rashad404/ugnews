<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid paddingX">
            <div class="row ">
                <div class="col-sm-12">
                    <h1 class="news_content"><?=$lng->get('News')?></h1>
                    <h4 class="news_content"><?=TextsModel::getText(2, 'News alt')?></h4>
                    <div class="row paddingTop20 paddingBottom40">
                        <div class="col-sm-12">
                            <div>
                                <div id="products" class="row list-group">
                                    <?php foreach ($data['list'] as $list):?>
                                        <div class="item col-lg-4">
                                            <div class="thumbnail news_card">
                                                <a href="news/<?=$list['id']?>/<?=Format::urlText($list['title_'.$data['def_language']])?>">
                                                    <img class="group list-group-image hoverable" src="<?=Url::filePath()?>/<?=$list['image']?>" alt="" />
                                                    <div class="caption">
                                                        <div class="caption_text">
                                                            <h4 class="group inner list-group-item-heading">
                                                                <strong><?=ProductsModel::formatListTitle($list['title_'.$data['def_language']])?></strong></h4>
                                                            <p class="group inner list-group-item-text">
                                                                <span style="color:gray"><?=ProductsModel::formatListText($list['text_'.$data['def_language']], 100)?></span></p>
                                                        </div>
                                                        <div class="row news_list_bottom">
                                                            <div class="col-6 col-md-6">
                                                                <p class="date"><?=date("M d Y",$list['time'])?></p>
                                                            </div>
                                                            <div class="col-6 col-md-6">
                                                                <a class="news_read_more" href="news/<?=$list['id']?>/<?=Format::urlText($list['title_'.$data['def_language']])?>"><?=strtoupper($lng->get('Read More'))?></a>
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

                </div>
            </div>

        </div>
    </section>
</main>
