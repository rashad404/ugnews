<?php
use Models\LanguagesModel;
$model = $data["model"];
$lang = $params['lang'];

$languages = LanguagesModel::getLanguages();
?>
<script>
    $(function() {
        $('#feature_select').change(function () {
            if ($('#feature_select').val() == 2) {
                $('#select_options').show();
            } else {
                $('#select_options').hide();
            }
        });

        // $('.ui.dropdown').dropdown();
        $('.ui.dropdown').dropdown('set selected',<?=$model['features']?>);
    });
</script>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12 secimet">
            <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Feature Group\'s Name')?></strong></label>
                        <input placeholder="<?=$lang->get('Example')?>: <?=$lang->get('Color')?>" type="text" id="name" name="name" value="<?=$model?$model["name"]:''?>" class="form-control admininput">
                    </div>
                </div>

                <div class="col-sm-12" id="select_options">

                    <div class="form-group">
                        <label for="email"><?=$lang->get('Select Features')?></label>
                        <select id="multiple-select" class=" ui fluid search normal dropdown" multiple name="features[]">
                            <?php foreach ($data['feature_list'] as $feature):?>
                                <option value="<?=$feature['id']?>"><?=$feature['name']?></option>
                            <?php endforeach;?>
                        </select>
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