<?php
use Models\LanguagesModel;
$model = $data["model"];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">

        <div class="col-sm-12">
            <div class="form-group">
                <label><strong>Title</strong></label>
                <input type="text" id="title" name="title" value="<?=$model?$model["title"]:''?>" class="form-control admininput width_50">
            </div>
        </div>
        <div class="col-xs-12 secimet tab-content">
            <?php
            foreach($languages as $k => $language){
            ?>
            <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong>Text</strong></label>
                        <textarea class="summernote" name="text"><?=$model?$model["text"]:''?></textarea>
                    </div>
                </div>
            </div>
            <?php } ?>
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
            <div class="input-group pull-right">
                <div class="pos-rel-top-6 ">
                    <label class="padyan15">Aktiv</label>
                    <input class="admin-switch" data-on-text="" data-off-text="" id="status" type="checkbox" name="status" value="1" <?php if($model && $model["status"]==0) echo ""; else echo "checked";?>>
                </div>
            </div>
        </div>
    </div>
</form>
