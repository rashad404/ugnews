<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;

?>
<main class="main boutiques">
    <section xmlns="http://www.w3.org/1999/xhtml">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2">
                    <div class="row">
                        <?php include 'app/templates/main/layouts/inc/left-sidebar.php';?>
                    </div>
                </div>
                <div class="col-sm-10">
                    <h1 class="title"><?=$data['pageTitle']?></h1>
                    <div class="default" style="float: left">
                        <form action="" method="post">
                            <?=$lng->get('Order')?>:
                            <select name="products_order" style="width:140px"onchange="this.form.submit()">
                                <option value="last"<?=($data['products_order']=='last')?'selected':''?>><?=$lng->get('Recent')?></option>
                                <option value="low_price" <?=($data['products_order']=='low_price')?'selected':''?>><?=$lng->get('Price: Low to High')?></option>
                                <option value="high_price" <?=($data['products_order']=='high_price')?'selected':''?>><?=$lng->get('Price: High to Low')?></option>
                            </select>
                        </form>
                    </div>
                    <div class="mobile_filter" style="float: right">
                        <button class="btn btn-primary visible-xs" id="mobile_filter"><?=$lng->get('Filter')?></button>
                    </div>
                    <div class="clearBoth"></div>
                    <div class="row paddingTop20">
                        <div class="col-sm-12">
                            <div>
                                <div id="products" class="row list-group">
                                    <?php foreach ($data['products'] as $product):?>
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
                                <?php echo $data["pagination"]->pageNavigation('pagination')?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>
