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
                        <label><strong>Haqqımda</strong></label>
                        <textarea id="summernote" name="adi_<?= $language["name"] ?>"><?=$model?$model["adi_".$language["name"]]:''?></textarea>
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
