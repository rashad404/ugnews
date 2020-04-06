<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;

?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid paddingX paddingY">
            <div class="row">
                <div class="col-sm-3 heading">
                    <h3 class="heading"><?=$lng->get('Photo Gallery')?></h3>
                    <p><?=TextsModel::getText(3, 'Media alt')?></p>
                </div>
                <div class="col-sm-9">
                    <ul class="clearfix media">
                        <?php foreach ($list as $item):?>
                            <li class="media">
                                <div class="gallery-grid">
                                    <img src="<?=Url::uploadPath().$item['thumb']?>" alt="<?=$item['title_'.$data['def_language']]?>" class="img-fluid">
                                    <div class="p-mask">
                                        <h4 class="img_title"><?=$item['title_'.$data['def_language']]?></h4>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach;?>
                    </ul>
                    <?php echo $data["pagination"]->pageNavigation('pagination')?>
                </div>
            </div>

        </div>
    </section>
</main>
