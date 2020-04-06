<?php
use Helpers\Url;
use Helpers\Format;
use Models\SeoModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h1><?=ucfirst($item['title'])?></h1>
            <p>
                <span><i class="fa fa-user"></i> Housing </span>
                <span><i class="fa fa-calendar"></i> <?=date("M d, Y",$item['time'])?> </span>
                <span><i class="fa fa-eye"></i> <?=$item['view']?></span>
            </p>
        </div>
        <div class="container">
            <div class="row paddingTop40 paddingBottom40">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog_image">
                                <?php if(!empty($item['image'])):?>
                                    <img src="<?=Url::filePath()?>/<?=$item['image']?>" alt="<?=ucfirst($item['title'])?>" />
                                <?php else:?>
                                    <img src="<?=SeoModel::getPhoto($item['title'])?>" alt="<?=ucfirst($item['title'])?>" />
                                <?php endif;?>
                            </div>
                            <?php if(!empty($item['text'])):?>
                                <div class="blog_inner_text">
                                    <?=html_entity_decode($item['text'])?>
                                    <div class="clearBoth"></div>
                                </div>
                            <?php else:?>
                                <div class="blog_inner_text_find">
                                    <?=SeoModel::getSeotext($item['title'])?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog_inner_navigate">
                                <?php if($previous_item['id']>0):?><div style="float:left;"><a href="find/<?=Format::urlText($previous_item['title'])?>"><<< <?=$lng->get('Previous Blog')?></a></div><?php endif;?>
                                <?php if($next_item['id']>0):?><div style="float:right;"><a href="find/<?=Format::urlText($next_item['title'])?>"><?=$lng->get('Next Blog')?> >>></a></div><?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="sidebar sidebar_search">
                        <form action="find/search/index" method="POST">
                            <input type="text" placeholder="<?=$lng->get('Search')?>.." name="search"><button class="btn btn-theme" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="sidebar sidebar_popular">
                        <div class="sidebar_title"><i class="fa fa-clock"></i><?=$lng->get('Popular posts')?></div>
                        <div class="sidebar_content">
                            <?php foreach ($data['popular_list'] as $list):?>
                            <div class="popular_post">
                                <?php if(!empty($list['image'])):?>
                                    <img src="<?=Url::filePath()?>/<?=$list['image']?>" alt="<?=Format::listTitle($list['title'], 100)?>"/>
                                <?php else:?>
                                    <img src="<?=SeoModel::getPhoto($list['title'])?>" alt="<?=Format::listTitle($list['title'], 100)?>"/>
                                <?php endif;?>

                                <a href="find/<?=Format::urlText($list['title'])?>" class="popular_post_title"><?=Format::listTitle($list['title'], 50)?></a>
                                <div class="popular_post_date"><?=date('M d, Y',$list['time'])?></div>
                                <div class="clearBoth"></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
