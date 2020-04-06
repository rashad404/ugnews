<?php
use Helpers\Url;
use Helpers\Format;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid paddingX">
            <div class="row paddingTop40 paddingBottom40">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <div class="news_inner_card">
                        <img src="<?=Url::filePath()?>/<?=$item['image']?>" alt="" />
                        <div class="news_inner_list_bottom">
                            <div class="news_inner_date"><?=$item['title_'.$def_language]?></div>
                            <div class="news_inner_title"><?=date("M d Y",$item['time'])?></div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1"></div>
            </div>
            <div class="row paddingBottom40">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <div class="news_inner_text">
                        <?=html_entity_decode($item['text_'.$def_language])?>
                    </div>
                    <div class="news_inner_navigate">
                        <?php if($previous_item['id']>0):?><div class="news_inner_date"><a href="news/<?=$previous_item['id']?>/<?=Format::urlText($previous_item['title_'.$data['def_language']])?>"><<< <?=$lng->get('Previous News')?></a></div><?php endif;?>
                        <?php if($next_item['id']>0):?><div class="news_inner_title"><a href="news/<?=$next_item['id']?>/<?=Format::urlText($next_item['title_'.$data['def_language']])?>"><?=$lng->get('Next News')?> >>></a></div><?php endif;?>
                    </div>
                </div>
                <div class="col-lg-1"></div>
            </div>
        </div>

    </section>
</main>
