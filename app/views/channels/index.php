<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html" class="section-wrapper back-pattern-1">
        <div class="container">
            <div class="row ">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h4><?=$lng->get('Blog')?></h4>
                        <h2><?=ProductsModel::formatListText(TextsModel::getText(2, 'Blog alt'))?></h2>

                            <?=TextsModel::getText(4, 'Blog sub alt')?>

                    </div>
                    <div class="row paddingTop20 paddingBottom40">
                        <div class="col-sm-12 remove_col_padding">
                            <div>
                                <div id="products" class="row list-group">
                                    <?php foreach ($data['list'] as $list):?>
                                        <div class="item col-lg-4 remove_col_padding_mob">
                                            <div class="thumbnail blog_card">
                                                <img src="<?=Url::filePath()?>/<?=$list['thumb']?>" alt="" />
                                                <div>
                                                    <div class="post_text">
                                                        <h5><?=ProductsModel::formatListTitle($list['title_'.$data['def_language']])?></h5>
                                                        <p class="blog_date"><?=date("M d, Y",$list['time'])?> / <?=$lng->get('Writer')?>: Admin</p>
                                                        <div class="post-desc">
                                                            <p><?=ProductsModel::formatListText($list['text_'.$data['def_language']], 100)?></p>
                                                        </div>
                                                        <div class="post_read_more">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-7 col-sm-6 col-6 post-alt">
                                                                    <h5><i class="fa fa-eye"></i> <?=$list['view']?> </h5>
                                                                </div>
                                                                <div class="col-md-5 col-sm-6 col-6">
                                                                    <a href="blog/<?=$list['id']?>/<?=Format::urlText($list['title_'.$data['def_language']])?>" class="text-center btn-theme"><?=strtoupper($lng->get('Read More'))?></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
