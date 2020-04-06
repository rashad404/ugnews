<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><?=$item['title_'.$def_language]?></h3>
            <p>
                <span><i class="fa fa-user"></i> Admin </span>
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
                                <img src="<?=Url::filePath()?>/<?=$item['image']?>" alt="" />
                            </div>
                            <div class="blog_inner_text">
                                <?=html_entity_decode($item['text_'.$def_language])?>
                                <div class="clearBoth"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog_inner_navigate">
                                <?php if($previous_item['id']>0):?><div style="float:left;"><a href="blog/<?=$previous_item['id']?>/<?=Format::urlText($previous_item['title_'.$data['def_language']])?>"><<< <?=$lng->get('Previous Blog')?></a></div><?php endif;?>
                                <?php if($next_item['id']>0):?><div style="float:right;"><a href="blog/<?=$next_item['id']?>/<?=Format::urlText($next_item['title_'.$data['def_language']])?>"><?=$lng->get('Next Blog')?> >>></a></div><?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="sidebar sidebar_search">
                        <form action="blog/search/index" method="POST">
                            <input type="text" placeholder="<?=$lng->get('Search')?>.." name="search"><button class="btn btn-theme" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="sidebar sidebar_popular">
                        <div class="sidebar_title"><i class="fa fa-clock"></i><?=$lng->get('Popular posts')?></div>
                        <div class="sidebar_content">
                            <?php foreach ($data['popular_list'] as $list):?>
                            <div class="popular_post">
                                <img src="<?=Url::filePath()?>/<?=$list['image']?>" alt="<?=Format::listTitle($list['title_'.$data['def_language']], 100)?>"/>
                                <a href="blog/<?=$list['id']?>/<?=Format::urlText($list['title_'.$data['def_language']])?>" class="popular_post_title"><?=Format::listTitle($list['title_'.$data['def_language']], 50)?></a>
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
