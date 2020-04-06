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
                    <label for="image">Şəkil</label>
                    <div class="slim" style="height:225px!important;"
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
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Country')?>:</strong></label><br/>
                    <select name="country" class="form-control">
                        <option value="0">---</option>
                        <?php foreach ($data['countries'] as $country):?>
                            <option <?=($model&&$model["country"]==$country['id'])?'selected':''?> value="<?=$country['id']?>"><?=$country['title_'.$defaultLanguage]?></option>
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
                    <label><strong><?=$lng->get('Price')?> (<?=DEFAULT_CURRENCY?>):</strong></label><br/>
                    <input class="form-control admininput" type="text" placeholder="<?=DEFAULT_CURRENCY?>" name="price" value="<?=$model?$model["price"]:''?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label><strong><?=$lng->get('Duration')?> (<?=$lng->get('night')?>):</strong></label><br/>
                    <input class="form-control admininput" type="text" placeholder="<?=$lng->get('night')?>" name="duration" value="<?=$model?$model["duration"]:''?>">
                </div>
            </div>


            <div class="col-sm-2">
                <div class="form-group">
                    <label><strong><?=$lng->get('Hotel star')?>:</strong></label><br/>
                    <select name="star" class="form-control">
                        <option value="0">---</option>
                        <?php $stars_array = [1,2,3,4,5];?>
                        <?php foreach ($stars_array as $star):?>
                            <option <?=($model&&$model["star"]==$star)?'selected':''?> value="<?=$star?>"><?=$star?> <?=$lng->get('star')?></option>
                        <?php endforeach;?>
                    </select>
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
        </div>
        <div class="row">
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
