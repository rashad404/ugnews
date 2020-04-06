<?php
use Models\LanguagesModel;
use Modules\admin\Models\CategoriesModel;

$model = $data["model"];
$lang = $params['lang'];
$languages = LanguagesModel::getLanguages();
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12 secimet">
            <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Category')?></strong></label>
                        <input placeholder="" type="text" id="name" name="name" value="<?=$model?$model["name"]:''?>" class="form-control admininput">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Feature template')?></strong></label>
                        <div class="form-group">
                            <select id="feature_select" class="form-control" name="template">
                                <?php foreach($data['feature_templates_list'] as $feature_templates):?>
                                <option <?= $model['template']==$feature_templates['id'] ? 'selected' : ''?> value="<?=$feature_templates['id']?>"><?=$feature_templates['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong><?=$lang->get('Sub category')?></strong></label>
                        <div class="form-group">
                            <select class="form-control" name="parent">
                                <option value="0">---</option>
                                <?= CategoriesModel::buildCategoryOptionList(CategoriesModel::getCategoryList(), $model['parent']); ?>
                            </select>
                        </div>
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
