<?php
use Helpers\Url;
use Helpers\Csrf;
use Helpers\Format;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html" class="back-pattern-2">
        <div class="container-fluid inner_background">
            <h3><?=$item['title']?></h3>
            <p>
                <span><i class="fa fa-user"></i> <?=$item['first_name']?> </span>
                <span><i class="fa fa-calendar"></i> <?=date("M d, Y",$item['time'])?> </span>
                <span><i class="fa fa-eye"></i> <?=$item['view']?></span>
            </p>
        </div>
        <div class="container back-pattern-2">
            <div class="row paddingTop40">
                <div class="col-md-12 breadcrumbs">
                    <a href=""><?=$lng->get('Home')?></a> /
                    <a href="forum"><?=$lng->get('Forum')?></a> /
                    <a href="forum/cat/<?=Format::urlText($item['cat'])?>/<?=$item['cat_name']?>"><?=$item['cat_name']?></a> /
                    <?=$item['title']?>
                </div>
            </div>
            <div class="row paddingTop40 paddingBottom40">
                <div class="col-md-10">

                    <div class="row forum_starter_row">
                        <div class="col-xs-12 col-md-2 forum_profile">
                            <div class="col-xs-2 col-md-12">
                                <?php if (file_exists(Url::uploadPath() . 'profile/' . $item['user_id'] . '.jpg')):?>
                                    <img style="width:100%" src="<?= Url::uploadPath() . 'profile/' . $item['user_id'] ?>.jpg?ref=<?= rand(1111111, 9999999) ?>" title="">
                                <?php else: ?>
                                    <img style="width:100%" src="<?= URL::templatePath() ?>/img/profile_photo-02.png" title="">
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-10 col-md-12">
                                <div class="user_name"><?=$item['first_name']?> <?=$item['last_name']?></div>
                                <div class="forum_time visible-xs"><?=date('M d Y, H:i',$item['time'])?></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <div class="forum_time visible-lg"><?=date('M d Y, H:i',$item['time'])?></div>
                            <?=html_entity_decode($item['text'])?>
                            <div class="clearBoth"></div>
                        </div>
                    </div>

                    <?php foreach ($data['answer_list'] as $list):?>
                        <div class="row forum_starter_row">
                            <div class="col-xs-12 col-md-2 forum_profile">
                                <div class="col-xs-2 col-md-12">
                                    <?php if (file_exists(Url::uploadPath() . 'profile/' . $list['user_id'] . '.jpg')):?>
                                        <img style="width:100%" src="<?= Url::uploadPath() . 'profile/' . $list['user_id'] ?>.jpg?ref=<?= rand(1111111, 9999999) ?>" title="">
                                    <?php else: ?>
                                        <img style="width:100%" src="<?= URL::templatePath() ?>/img/profile_photo-02.png" title="">
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-10 col-md-12">
                                    <div class="user_name"><?=$list['first_name']?> <?=$list['last_name']?></div>
                                    <div class="forum_time visible-xs"><?=date('M d Y, H:i',$list['time'])?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-10">
                                <div class="forum_time visible-lg"><?=date('M d Y, H:i',$list['time'])?></div>
                                <?=html_entity_decode($list['text'])?>
                                <div class="clearBoth"></div>
                            </div>
                        </div>
                    <?php endforeach;?>

                    <div class="row forum_reply">
                        <div class="col-sm-12">
                            <h3><?=$lng->get('Write an answer')?></h3>
                            <div class="default">
                                <?php if($data['userId']<1):?>
                                    <div class="warning_text">
                                        <?=$lng->get('You must be logged in to answer')?>.
                                        <a href="login"><?=$lng->get('Sign in')?></a>
                                    </div><br/>
                                    <script>
                                        $(document).ready(function() {
                                            $('#summernote').summernote('disable');
                                        });
                                    </script>
                                <?php endif;?>
                                <form action="" method="post">
                                    <textarea name="text" id="summernote" placeholder="<?=$lng->get('Write your question')?>"></textarea>
                                    <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                                    <button <?= ($data['userId']<1)?'disabled':''?> class="btn default_button"><?=$lng->get('Write')?></button>
                                </form>
                            </div>
                        </div>

                </div>

                <div class="col-md-2">
                    <div class="sidebar sidebar_search">

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
