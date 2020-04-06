<?php
use Models\LanguagesModel;
$model = $data["model"];
$languages = LanguagesModel::getLanguages();

?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12 secimet tab-content">
            <?php
            foreach($languages as $k => $language){
            ?>
            <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong>Adı</strong></label>
                    <input type="text" id="adi" name="adi_<?= $language["name"] ?>" value="<?=$model?$model["adi_".$language["name"]]:''?>" class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong>Ünvanı</strong></label>
                    <input type="text" id="unvani" name="unvani_<?= $language["name"] ?>" value="<?=$model?$model["unvani_".$language["name"]]:''?>" class="form-control admininput">
                </div>
            </div>
            </div>
            <?php } ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Telefon</strong></label>
                    <input type="text" id="phone" name="phone" value="<?=$model?$model["phone"]:''?>" class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>İş saatları</strong></label>
                    <input type="text" id="working_hours" name="working_hours" value="<?=$model?$model["working_hours"]:''?>" class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Xəritədə yeri ( embed ) </strong></label>
                    <input type="text" id="maplink" name="maplink" value="<?=$model?$model["maplink"]:''?>" class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Virtual 360<sup>o</sup> link</strong></label>
                    <input type="text" id="virtuallink" name="virtuallink" value="<?=$model?$model["virtuallink"]:''?>" class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
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
                        <input type="file" name="image[]" required value="<?= empty($model['image']) ? '' : \Helpers\Url::filePath().$model['image'] ?>" />
                    </div>
                </div>
            </div>
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
