<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
$product_info = $data['productInfo'];
$product_photos = json_decode($product_info['photos']);
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container">
            <div class="row product_inner">
                <div class="col-sm-12">
                    <div class="row paddingTop20">
                        <div class="col-sm-5">
                            <div class="text-center">
                                <?php if(count($product_photos)>0):?>
                                <div class="slider slider-for">
                                    <div>
                                        <img id="product_inner_main_img" class="product_inner_main" src="<?=Url::trendyolImgPath()?>/<?=$product_info['thumb']?>" alt="" />
                                    </div>
                                    <?php foreach($product_photos as $photo):?>
                                    <div>
                                        <img class="product_inner_main" src="<?=Url::trendyolImgPath().$photo?>" alt="" />
                                    </div>
                                    <?php endforeach;?>
                                </div>
                                <div class="slider slider-nav">
                                    <div>
                                        <img class="product_inner_more" src="<?=Url::trendyolImgPath()?>/<?=$product_info['thumb']?>" alt="" />
                                    </div>
                                    <?php foreach($product_photos as $photo):?>
                                    <div>
                                        <img class="product_inner_more" src="<?=Url::trendyolImgPath().$photo?>" alt="" />
                                    </div>
                                    <?php endforeach;?>
                                </div>
                                <?php else:?>
                                    <img id="product_inner_main_img" class="product_inner_main" src="<?=Url::trendyolImgPath().$product_info['thumb']?>" alt="" />
                                <?php endif;?>
                            </div>

                        </div>
                        <div class="col-sm-7">
                            <p><h2 class="product_inner_title"><?=ProductsModel::formatInnerTitle($product_info['title_'.$data['def_language']])?></h2></p>
                            <p><h3 class="product_inner_price"><?=$product_info['price']?> <?=DEFAULT_CURRENCY?></h3></p>
                            <p class="product_inner_card">
                            <div class="product_inner_select">
                                <label>
                                    <select  id="inner_select_quantity">
                                        <?php for($i=1;$i<=100;$i++):?>
                                            <option value="<?=$i?>"><?=$i?></option>
                                        <?php endfor;?>
                                    </select>
                                </label>
                            </div>
                                <button type="submit" class="btn btn-primary cart" id="add_to_card_inner" data-id="<?=$product_info['id']?>"><?=$lng->get('Add to cart')?></button>
                            </p>
                            <h4 class="product_inner"><?=$lng->get('Product Details')?></h4>
                            <p><?=ProductsModel::formatInnerText($product_info['text_'.$data['def_language']])?></p>
                            <p><?=ProductsModel::showFeatures($product_info['features'])?></p>
                        </div>
                    </div>

                    <div class="similar_products">
                        <h4 class="product_inner"><?=$lng->get('Users Also Bought')?></h4>
                        <div id="products" class="row list-group">
                            <?php foreach ($data['products_similar'] as $product):?>
                                <div class="item col-lg-3 col-xs-6">
                                    <div class="thumbnail product_card">
                                        <a class="item" href="product/<?=$product['id']?>/<?=Format::urlText($product['title_'.$data['def_language']])?>">
                                            <img class="group list-group-image hoverable" src="<?=Url::trendyolImgPath().$product['thumb']?>" alt="" />
                                            <div class="caption_text">
                                                <h4 class="group inner list-group-item-heading">
                                                    <span class="product_title"><?=ProductsModel::formatListTitle($product['title_'.$data['def_language']])?></span></h4>
                                                <p class="group inner list-group-item-text">
                                                    <span style="color:gray"><?=ProductsModel::formatListText($product['text_'.$data['def_language']])?></span></p>
                                            </div>
                                        </a>
                                        <div class="row add_to_card">
                                            <div class="col-xs-4 col-md-4">
                                                <p class="card_price"><?=$product['price']?> <?=DEFAULT_CURRENCY?></p>
                                            </div>
                                            <div class="col-xs-8 col-md-8 rashad">
                                                <button data-id="<?=$product['id']?>" class="btn-primary card_button"><?=$lng->get('Add to cart')?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>

        </div>
    </section>
</main>
