<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">

    <?php
    $tag_list = [
            'Coronavirus', 'Donald Trump', 'New York', 'Italy', 'Boris Johnson', 'Oil price'
    ];

    ?>
<!--    Why Us?-->
    <div class="container paddingBottom20">
            <div class="row paddingBottom40">
                <div class="col-sm-12">


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page_title paddingTop20 paddingBottom20">
                                <h2>
                                    <?=$lng->get('Featured')?>
                                </h2>
                                <hr/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($tag_list as $key => $val):?>
                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">

                                <a class="tag_box_a" href="tags/<?=Format::urlText($val)?>">
                                    <?php
                                        $rand = rand(14,26);
                                        if($rand>18 && $rand<22){
                                            $bold = 'font-weight:bold;';
                                        }else{
                                            $bold = '';
                                        }
                                    ?>
                                    <div class="tag_box" style="<?=$bold?>font-size: <?=$rand?>px;height: 60px;line-height: 40px;">
                                        #<?=Format::shortText($val,20)?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="page_title paddingTop20 paddingBottom20">
                                <h2>
                                    <?=$lng->get('Latest News')?>
                                </h2>
                                <hr/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($data['list'] as $list):?>
                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                                <div class="news_box">
                                    <a href="news/<?=$list['id']?>/<?=Format::urlText($list['title'])?>">
                                        <img class="news_image" src="<?=Url::filePath()?>/<?=$list['image']?>" alt="" />
                                        <div class="caption">
                                            <div class="news_title">
                                                <span>
                                                    <?=Format::listTitle($list['title'], 60)?>
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
