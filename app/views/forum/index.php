<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html" class="section-wrapper-inner">
        <div class="container">
            <div class="row ">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h4><?=$lng->get('Forum')?></h4>
                        <h2><?=ProductsModel::formatListText(TextsModel::getText(7, 'Forum alt'))?></h2>

                        <?=TextsModel::getText(8, 'Forum sub alt')?>

                    </div>
                    <div class="row paddingTop20 paddingBottom40">
                        <div class="col-sm-3">
                            <?php include 'left_sidebar.php';?>
                        </div>
                        <div class="col-sm-9 forum_content">
                            <div class="row">
                                <div class="col-xs-6">
                                    <a href="forum/ask" class="btn default_button"><?=$lng->get('Ask a question')?></a>
                                </div>
                                <div class="col-xs-6">
                                    <button class="forum_sidebar_toggle btn default_button"><?=$lng->get('Categories')?> <i class="fa fa-caret-down"></i></button>
                                </div>
                            </div>
                            <?php foreach ($data['list'] as $list): ?>
                                <div class="row forum_row">
                                    <div class="col-xs-2 col-sm-1 text-center"><?=$list['answers']?> <?=$lng->get('answers')?></div>
                                    <div class="col-xs-2 col-sm-1 text-center"><?=$list['view']?> <?=$lng->get('views')?></div>
                                    <div class="col-xs-8 col-sm-10">
                                        <a href="forum/<?=$list['id']?>/<?=Format::urlText($list['title'])?>"><?=$list['title']?></a><br/>
                                        <?=$list['first_name']?> <?=$list['last_name']?>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>
