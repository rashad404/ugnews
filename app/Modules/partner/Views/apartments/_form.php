<?php
use Models\LanguagesModel;

$model = $data["model"];
$lng = $data['lng'];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();
$model?$features = json_encode(explode(',',$model['features'])):$features='';
?>
<script>
    $(function() {
        // $('.ui.dropdown').dropdown();
        $('.ui.dropdown').dropdown('set selected',<?=$features?>);
    });
</script>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form_box">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="image">Photo</label>
                    <div class="slim" style="height:225px!important;"
                         data-label="Choose a photo"
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
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Location')?>:</strong></label><br/>
                    <select name="location" class="form-control">
                        <option value="0">---</option>
                        <?php foreach ($data['locations'] as $list):?>
                            <option <?=($model&&$model["location"]==$list['id'])?'selected':''?> value="<?=$list['id']?>"><?=$list['name']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Category')?>:</strong></label><br/>
                    <select name="category" class="form-control">
                        <option value="0">---</option>
                        <?php foreach ($data['categories'] as $category):?>
                            <option <?=($model&&$model["category"]==$category['id'])?'selected':''?> value="<?=$category['id']?>"><?=$category['title_'.$defaultLanguage]?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Size (Sq. feet)')?>:</strong></label><br/>
                    <input class="form-control admininput" type="text" placeholder="" name="size" value="<?=$model?$model["size"]:''?>">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Apartment Model')?>:</strong></label><br/>
                    <select name="apt_model" class="form-control">
                        <option value="0">---</option>
                        <?php foreach ($data['apt_models'] as $list):?>
                            <option <?=($model&&$model["apt_model"]==$list['id'])?'selected':''?> value="<?=$list['id']?>"><?=$list['name']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Available Date')?>:</strong></label><br/>
                    <input class="form-control admininput" type="date" placeholder="<?=DEFAULT_CURRENCY?>" name="start_date" value="<?=$model?date("Y-m-d",$model["start_time"]):date("Y-m-d")?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Address')?>:</strong></label><br/>
                    <input class="form-control admininput" type="text" placeholder="" name="address" value="<?=$model?$model["address"]:''?>">
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="email"><?=$lng->get('Services included')?>:</label>
                    <select id="multiple-select" class=" ui fluid search normal dropdown" multiple name="features[]">
                        <?php foreach ($data['feature_list'] as $feature):?>
                            <option value="f<?=$feature['id']?>"><?=$feature['title_'.$data['defaultLang'].'']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label><strong><?=$lng->get('Name')?></strong></label><br/>
                    <input class="form-control admininput" type="text" name="name" value="<?=$model?$model["name"]:''?>">
                </div>
            </div>
            <div class="col-sm-12">
                <?php
                foreach($languages as $k => $language){
                    ?>
                    <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                        <div class="form-group">
                            <label><strong><?=$lng->get('Title')?></strong></label><br/>
                            <input class="form-control admininput" type="text" name="title_<?= $language["name"] ?>" value="<?=$model?$model["title_".$language["name"]]:''?>">
                        </div>
                        <div class="form-group">
                            <label><strong><?=$lng->get('Text')?></strong></label>
                            <textarea id="summernote<?=$k?>"name="text_<?= $language["name"] ?>"><?=$model?$model["text_".$language["name"]]:''?></textarea>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Rent')?> (<?=DEFAULT_CURRENCY?>):</strong></label><br/>
                    <input class="form-control admininput" type="text" placeholder="<?=DEFAULT_CURRENCY?>" name="rent" value="<?=$model?$model["rent"]:''?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Utility')?> (<?=DEFAULT_CURRENCY?>):</strong></label><br/>
                    <input class="form-control admininput" type="text" placeholder="<?=DEFAULT_CURRENCY?>" name="utility" value="<?=$model?$model["utility"]:''?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Profit')?> (<?=DEFAULT_CURRENCY?>):</strong></label><br/>
                    <input class="form-control admininput" type="text" placeholder="<?=DEFAULT_CURRENCY?>" name="Profit" value="<?=$model?$model["profit"]:''?>">
                </div>
            </div>
        </div>
    </div><!-- /.box-body -->

    <div class="box-footer">
        <div class="col-xs-12">
            <div class="input-group pull-left">
                <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                <button type="submit" name="submit" class="btn btncolor secimetbtnadd">
                    Save
                </button>
            </div>
            <div class="input-group pull-right">
                <div class="pos-rel-top-6 ">
                    <label class="padyan15">Active</label>
                    <input class="admin-switch" data-on-text="" data-off-text="" id="status" type="checkbox" name="status" value="1" <?php if($model && $model["status"]==0) echo ""; else echo "checked";?>>
                </div>
            </div>
        </div>
    </div>
</form>
