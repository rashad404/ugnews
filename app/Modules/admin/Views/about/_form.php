<?php
use Models\LanguagesModel;

$model = $data["model"];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();

?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">

        <div class="col-xs-12 secimet tab-content">

            <?php
            foreach($languages as $k => $language){
                ?>
                <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label><strong>Haqqımızda</strong></label>
                        <textarea id="summernote" name="text_<?= $language["name"] ?>"><?=$model?$model["text_".$language["name"]]:''?></textarea>
                    </div>
                </div>
                </div>
                <?php }
            ?>
        </div>
    </div><!-- /.box-body -->

    <div class="box-footer">
        <div class="col-xs-12">
            <div class="input-group pull-left">
                <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                <button type="submit" name="submit" class="btn btncolor secimetbtnadd">
                    Yadda saxla
                </button>
            </div>
        </div>
    </div>
</form>
