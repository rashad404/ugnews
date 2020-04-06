<?php
use Helpers\Url;
use Models\TextsModel;
use Models\BlogModel;
use Helpers\Format;
?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-4">
                <div class="footer_block">
                    <div class="footer_logo">
                        <a href="" class="logo" style="display: block">
                            <img class="footer_logo" src="<?=Url::templatePath()?>/img/partner_logos/<?=$_PARTNER['header_logo_white']?>" alt="<?=PROJECT_NAME?> logo"/>
                        </a>
                    </div>
                    <div class="footer_about"><?= TextsModel::getText(1,'About Us'); ?></div>
                    <div class="footer_apps">
                        <a target="_blank" href="#">
                            <img src="<?=Url::templatePath()?>img/google_play.png" alt="<?=$lng->get('Android App')?>">
                        </a>
                        <a target="_blank" href="#">
                            <img src="<?=Url::templatePath()?>img/app_store.png" alt="<?=$lng->get('Ios App')?>">
                        </a>
                    </div>
                    <div class="clearBoth"></div>
                </div>
            </div>

            <div class="col-sm-4 col-4">
                <div class="footer_block">
                    <h2><?=$lng->get('Quick Links')?></h2>
                    <div class="footer_links_1">
                        <ul>
                            <li><a href="about"><?=$lng->get('About us')?></a></li>
                            <li><a href="contact-us"><?=$lng->get('Contact Us')?></a></li>
                        </ul>
                    </div>
                    <div class="footer_links_2">
                        <ul>
                            <li><a href="#"><?=$lng->get('Android App')?></a></li>
                            <li><a href="#"><?=$lng->get('Ios App')?></a></li>
                        </ul>
                    </div>
                    <div style="clear: both"></div>
                </div>
            </div>

            <div class="col-sm-4 col-4">
                <div class="footer_block">
                    <h2><?=$lng->get('Last posts')?></h2>


                    <div class="footer_blog">
                            <?php foreach (BlogModel::getList(2) as $item):?>
                            <div class="row">
                                <div class="footer_blog_item">
                                    <a href="blog/<?=$item['id']?>/<?=Format::urlText($item['title_'.$data['def_language']])?>">
                                        <img class="img-left" src="<?= Url::uploadPath().$item['thumb']?>" alt="<?= $item['title_'.$data['def_language']]?>"/>
                                        <div class="content-heading"><h4><?= $item['title_'.$data['def_language']]?></h4></div>
                                    </a>
                                        <p><?= substr(strip_tags(html_entity_decode($item['text_'.$data['def_language']])),0,100)?></p>

                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= GOOGLE_ANALYTICS;?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?= GOOGLE_ANALYTICS;?>');
</script>
<div class="footer_alt">
    <?= date("Y")?> <?=$_PARTNER['name']?>
    © <?=$lng->get('All rights reserved')?>
    <?php if($_PARTNER['id']==0):?>
    | <?=$lng->get('Created by')?> <a target="_blank" href="https://websiteca.com" title="Small business websites, website development">WebsiteCA</a>
    <?php endif;?>
</div>
