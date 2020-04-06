<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html" class="section-wrapper back-pattern-1">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("Testimonials")?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row ">
                <div class="col-sm-12">
                    <div class="row paddingTop40 paddingBottom20">
                        <div class="col-sm-12">
                            <div id="products" class="row list-group">
                                <div class="owl-carousel owl-theme" id="owl_1">
                                    <?php foreach ($data['list'] as $list):?>
                                        <div class="item testimonials_card">
                                            <div class="item">
                                                <img src="<?=Url::filePath()?>/<?=$list['thumb']?>" alt="" />
                                                <div class="testimonials_text">
                                                    <h5><?=ProductsModel::formatListTitle($list['title_'.$data['def_language']])?></h5>
                                                    <div class="testimonials_text">
                                                        <p><?=ProductsModel::formatListText($list['text_'.$data['def_language']], 100)?></p>
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
