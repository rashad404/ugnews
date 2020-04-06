<?php
use Models\LanguagesModel;
$model = $data["model"];

$languages = LanguagesModel::getLanguages("admin");
$defaultLanguage = $data['dataParams']['defaultLang'];
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
                        <label><strong>Menu adı</strong></label>
                        <input type="text" id="title_az" name="title_<?= $language["name"]?>" value="<?=$model?$model["title_".$language["name"]]:''?>" class="form-control admininput">
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="category">Alt Menyu</label>
                    <select name="parent_id" id="category" class="form-control admininput">
                        <option value="0">-</option>
				        <?php
				        foreach($data['menus'] as $key=>$val){
					        if($model && $model["parent_id"]==$val['id']) $selected='selected="selected"'; else $selected='';
					        echo '<option value="'.$val['id'].'" '.$selected.'>'.$val["title_".$defaultLanguage].'</option>';
				        }
				        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Ünvan (Əgər başqa saytdırsa http:// ilə yazın)</strong></label>
                    <input type="text" id="url" name="url" value="<?=$model?$model["url"]:''?>" class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group padvert30">
                    <?php $c=0; foreach ($params["menuType"] as $key => $value): ?>
                        <?php
                        if(isset($model["menu_type"])){
                            if($model["menu_type"] == $key){
	                            $checked = 'checked';
                            }else {
	                            $checked = '';
                            }
                        } else {
	                        if($c==0){
		                        $checked = 'checked';
	                        }else {
		                        $checked = '';
	                        }
                        }
                        $c++;
                        ?>
                        <label class="radio-inline"><input type="radio" name="menu_type" class="admininput adminradioinput" value="<?=$key?>" <?=$checked?>><strong class="top-2"> <?=$value?> </strong></label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <?php if($data["dataParams"]["posUp"] || $data["dataParams"]["posDown"]) { ?>
                    <div class="form-group padvert30">
                        <div class="col-md-6">
                            <?php if($data["dataParams"]["posUp"]) {
                                if($model["up"] == 1) {
                                    $checkedUp = 'checked';
                                } else {
                                    $checkedUp = '';
                                }
                                ?>
                                <div class="checkboxum">
                                    <label>
                                        <input type="checkbox" class="admininput" id="up" name="up" value="1" <?=$checkedUp?>>
                                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span> &emsp;Yuxarıda görünsün
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <?php if($data["dataParams"]["posDown"]) {
                                if($model["down"] == 1) {
                                    $checkedDown = 'checked';
                                } else {
                                    $checkedDown = '';
                                }
                                ?>
                                <div class="checkboxum">
                                    <label>
                                        <input class="admininput" type="checkbox" id="down" name="down" value="1" <?=$checkedDown?>>
                                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span> &emsp;Aşağıda görünsün
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="keyword">Açar sözlər</label>
                    <input class="form-control admininput" id="keyword" name="tags" value="<?=$model?$model["tags"]:''?>">
                </div>
            </div>
            <div class="col-sm-6 col-lg-offset-6">
                <div class="form-group">
                    <label for="meta_description">Meta açıqlama</label>
                    <input class="form-control admininput" name="meta_description" id="meta_description" value="<?=$model?$model["meta_description"]:''?>">
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
