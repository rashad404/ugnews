<?php
use Models\LanguagesModel;
$model = $data["model"];
if(!empty($model["select_options"])) {
    $model["select_options"] = implode(', ', json_decode($model["select_options"]));
}
$lang = $params['lang'];
$languages = LanguagesModel::getLanguages();
?>
<script>
    $(function() {
        $('#feature_select').change(function () {
           if ($('#feature_select').val() == 2 || $('#feature_select').val() == 3) {
               $('#select_options').show();
           } else {
               $('#select_options').hide();
           }
        });
    });
</script>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12 secimet">
            <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Feature')?></strong></label>
                        <input placeholder="<?=$lang->get('Example')?>: <?=$lang->get('Color')?>" type="text" id="name" name="name" value="<?=$model?$model["name"]:''?>" class="form-control admininput">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Feature type')?></strong></label>
                        <div class="form-group">
                            <select id="feature_select" class="form-control" name="type">
                                <option value="1"><?=$lang->get('Text')?></option>
                                <option <?=$model["type"]==2?'selected':''?> value="2"><?=$lang->get('Select box')?></option>
                                <option <?=$model["type"]==3?'selected':''?> value="3"><?=$lang->get('Multi Select box')?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12" id="select_options" <?=$model["type"]==1?'style="display:none"':''?>>
                    <div class="form-group">
                        <label><strong><?=$lang->get('Select options')?></strong></label> (<?=$lang->get('Split by comma')?>)
                        <input placeholder="<?=$lang->get('Example')?>: <?=$lang->get('White')?>, <?=$lang->get('Black')?>, <?=$lang->get('Red')?>" type="text" id="select_options" name="select_options" value="<?=$model?$model["select_options"]:''?>" class="form-control admininput">
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
