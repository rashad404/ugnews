<?php
use Models\LanguagesModel;
$model = $data["model"];
$lang = $params['lang'];

$languages = LanguagesModel::getLanguages();
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12 secimet tab-content">
            <?php
            foreach($languages as $k => $language){
            ?>
            <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Question')?></strong></label>
                        <input type="text" id="title_az" name="title_<?= $language["name"]?>" value="<?=$model?$model["title_".$language["name"]]:''?>" class="form-control admininput">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Answer')?></strong></label>
                        <input type="text" id="text_az" name="text_<?= $language["name"]?>" value="<?=$model?$model["text_".$language["name"]]:''?>" class="form-control admininput">
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
                    <?=$lang->get('Save')?>
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
