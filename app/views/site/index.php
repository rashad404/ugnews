<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">

    <?php
    if($data['region']==16) {
        $tag_list = [
            'Koronavirus', 'Türkiyə', 'New York', 'Hava', 'Neft qiyməti', 'Evdə qal', 'Dövlət yardımı', '8103 SMS'
        ];
    }else{
        $tag_list = [
            'Coronavirus', 'Donald Trump', 'New York', 'Italy', 'Boris Johnson', 'Oil price'
        ];
    }
    ?>

    <div class="container paddingBottom20">
            <div class="row paddingBottom40">
                <div class="col-sm-12">


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page_title paddingTop20 paddingBottom20">
                                <h2>
                                    <?=$lng->get('Popular Channels')?>
                                </h2>
                                <hr/>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <?php foreach ($data['channel_list'] as $list):?>
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
                                <div class="channel_box">
                                    <a href="/<?=Format::urlTextChannel($list['name_url'])?>">
                                        <img src="<?=Url::filePath()?>/<?=$list['image']?>" alt="" />

                                        <div class="news_title">
                                            <span>
                                                <?=Format::listTitle($list['name'], 50)?>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page_title paddingBottom20">
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

                                <a class="tag_box_a" href="tags/<?=Format::urlTextTag($val)?>">
                                    <?php
                                        $rand = rand(13,22);
                                        if($rand>16 && $rand<19){
                                            $bold = 'font-weight:bold;';
                                        }else{
                                            $bold = '';
                                        }
                                    ?>
                                    <div class="tag_box" style="<?=$bold?>font-size: <?=$rand?>px;height: 50px;line-height: 40px;">
                                        #<?=Format::shortText($val,20)?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="page_title paddingBottom20">
                                <h2>
                                    <?=$lng->get('Latest News')?>
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
