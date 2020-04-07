<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">

    <?php
    $company_list[] = ['name'=>'NAR Housing', 'logo'=> 'nar_housing.png', 'url'=>'https://narhousing.com', 'info'=>'Co-living, Student Housing'];
    $company_list[] = ['name'=>'WebsiteCA', 'logo'=> 'websiteca.jpg', 'url'=>'https://websiteca.com', 'info'=>'Website Development, Applications'];
    $company_list[] = ['name'=>'Lord Housing', 'logo'=> 'lord_housing.jpg', 'url'=>'https://lordhousing.com', 'info'=>'Student Housing Company'];


    ?>
<!--    Why Us?-->
    <div class="container paddingBottom20">
            <div class="row paddingBottom40">
                <div class="col-sm-12">


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page_title paddingTop20 paddingBottom20">
                                <h2>
                                    <?=$lng->get('Top Tags')?>
                                </h2>
                                <hr/>
                            </div>
                        </div>

                        <?php foreach ($data['list'] as $list):?>
                            <div class="item col-lg-3">
                                <div class="news_box">
                                    <a href="news/<?=$list['id']?>/<?=Format::urlText($list['title'])?>">
                                        <img class="news_image" src="<?=Url::filePath()?>/<?=$list['image']?>" alt="" />
                                        <div class="caption">
                                            <div class="news_title">
                                                <span>
                                                    <?=ProductsModel::formatListText($list['title'], 60)?>
                                                </span>
                                            </div>
                                            <div class="row news_date">
                                                <div class="col-xs-6 col-md-6">
                                                    <?=date("M d Y",$list['time'])?>
                                                </div>
                                                <div class="col-xs-6 col-md-6">
                                                    <span style="float:right;"><?=date("H:i",$list['time'])?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>


                        <div class="col-lg-12">
                            <div class="page_title paddingTop20 paddingBottom20">
                                <h2>
                                    <?=$lng->get('Latest News')?>
                                </h2>
                                <hr/>
                            </div>
                        </div>

                        <?php foreach ($data['list'] as $list):?>
                            <div class="item col-lg-3">
                                <div class="news_box">
                                    <a href="news/<?=$list['id']?>/<?=Format::urlText($list['title'])?>">
                                        <img class="news_image" src="<?=Url::filePath()?>/<?=$list['image']?>" alt="" />
                                        <div class="caption">
                                            <div class="news_title">
                                                <span>
                                                    <?=ProductsModel::formatListText($list['title'], 60)?>
                                                </span>
                                            </div>
                                            <div class="row news_date">
                                                <div class="col-xs-6 col-md-6">
                                                    <?=date("M d Y",$list['time'])?>
                                                </div>
                                                <div class="col-xs-6 col-md-6">
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
