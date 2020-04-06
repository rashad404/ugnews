<?php
use Models\TextsModel;
use Helpers\Format;
use Helpers\Csrf;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html" class="section-wrapper">
        <div class="container">
            <div class="row ">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h4><?=$lng->get('Forum')?></h4>
                        <h2><?=Format::listText(TextsModel::getText(7, 'Forum alt'))?></h2>
                        <?=TextsModel::getText(8, 'Forum sub alt')?>
                    </div>
                    <div class="row paddingTop20 paddingBottom40">
                        <div class="col-sm-3">
                            <?php include 'left_sidebar.php';?>
                        </div>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3><?=$lng->get('Ask a question')?></h3>
                                    <div class="default">
                                        <?php if($data['userId']<1):?>
                                            <div class="warning_text">
                                                <?=$lng->get('You must be logged in to ask a question')?>.
                                                <a href="login"><?=$lng->get('Sign in')?></a>
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    $('#summernote').summernote('disable');
                                                });
                                            </script>
                                        <?php endif;?>
                                        <form action="" method="post">

                                            <label><?=$lng->get('Title')?></label><br/>
                                            <input <?= ($data['userId']<1)?'disabled':''?> type="text" name="title" value="" placeholder=""/>

                                            <label><?=$lng->get('Your question')?></label><br/>
                                            <textarea name="text" id="summernote" placeholder="<?=$lng->get('Write your question')?>"></textarea>

                                            <select name="cat">
                                                <option disabled selected><?=$lng->get('Select a category')?></option>
                                                <?php foreach ($data['category_list'] as $list): ?>
                                                    <option value="<?=$list['id']?>"><?=$list['title_'.$data['def_language']]?></option>
                                                <?php endforeach;?>
                                            </select>

                                            <label><?=$lng->get('Tags')?></label><br/>
                                            <input class="tags_input" value="turizm" data-role="tagsinput" <?= ($data['userId']<1)?'disabled':''?> type="text" name="tags"/><br/><br/>

                                            <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                                            <button <?= ($data['userId']<1)?'disabled':''?> class="btn default_button"><?=$lng->get('Post your question')?></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
    </section>
</main>
