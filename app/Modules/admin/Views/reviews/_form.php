<?php
use Models\LanguagesModel;

$model = $data["model"];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();

?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">

        <div class="col-xs-12 secimet tab-content">

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="image">Şəkil</label>
                    <div class="slim"
                         data-label="Şəkil seçin"
                         data-label-loading=""
                         data-button-edit-label=""
                         data-button-remove-label=""
                         data-button-upload-label=""
                         data-button-cancel-label="Cancel"
                         data-button-confirm-label="Ok"
                         data-rotation="90"
                         data-size="9000,9000">
                        <?php if(!empty($model['image'])): ?>
                            <img src="<?=\Helpers\Url::filePath().$model['image']?>">
                        <?php endif; ?>
                        <input type="file" name="image[]"  value="<?= empty($model['image']) ? '' : \Helpers\Url::filePath().$model['image'] ?>" />
                    </div>
                </div>
            </div>
            <?php
            foreach($languages as $k => $language){
                ?>
                <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label><strong>Adı</strong></label><br/>
                            <input type="text" name="adi_<?= $language["name"] ?>" value="<?=$model?$model["adi_".$language["name"]]:''?>">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label><strong>Vəzifəsi</strong></label><br/>
                            <input type="text" name="job_<?= $language["name"] ?>" value="<?=$model?$model["job_".$language["name"]]:''?>">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label><strong>Mətn</strong></label>
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
            <div class="input-group pull-right">
                <div class="pos-rel-top-6 ">
                    <label class="padyan15">Aktiv</label>
                    <input class="admin-switch" data-on-text="" data-off-text="" id="status" type="checkbox" name="status" value="1" <?php if($model && $model["status"]==0) echo ""; else echo "checked";?>>
                </div>
            </div>
        </div>
    </div>
</form>
