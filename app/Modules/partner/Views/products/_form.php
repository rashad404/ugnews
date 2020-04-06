<?php
use Models\LanguagesModel;
use Helpers\Database;
use Modules\partner\Models\CategoriesModel;
use Helpers\Url;

$model = $data["model"];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();

$lang = $params['lang'];
?>
<script>
    $(function () {
        $( "#features_a" ).click(function() {
            $('#features_tab').show();
            $('#general_tab').hide();
            $('#photos_tab').hide();
            $(this).addClass('active');
            $('#general_a').removeClass('active');
            $('#photos_a').removeClass('active');
        });
        $( "#general_a" ).click(function() {
            $('#features_tab').hide();
            $('#photos_tab').hide();
            $('#general_tab').show();
            $(this).addClass('active');
            $('#features_a').removeClass('active');
            $('#photos_a').removeClass('active');
        });
        $( "#photos_a" ).click(function() {
            $('#features_tab').hide();
            $('#general_tab').hide();
            $('#photos_tab').show();
            $(this).addClass('active');
            $('#features_a').removeClass('active');
            $('#general_a').removeClass('active');
        });
    })
</script>
<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12">

            <ul class="nav nav-tabs nav-justified nav-features">
                <li class="active" id="general_a"><a><?=$lang->get('General')?></a></li>
                <?php if($model["cat"]>0){?>
                <li id="features_a"><a><?=$lang->get('Features')?></a></li>
                <?php }?>
                <li id="photos_a"><a><?=$lang->get('Photos')?></a></li>
            </ul>

            <div id="general_tab" class="general_tab">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="image"><?=$lang->get('Photo')?></label>
                            <div>
                                <img style="max-height: 200px;" src="<?=$model['thumb']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><strong>Kateqoriya</strong></label><br/>
                            <select class="form-control" name="cat" id="category">
                                <?php CategoriesModel::buildCategoryOptionList(CategoriesModel::getCategoryList(), $model['cat']); ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                    <div class="form-group">
                        <label><strong>Qiymət (<?=DEFAULT_CURRENCY?>)</strong></label><br/>
                        <input class="form-control" type="text" name="price" value="<?=$model?$model["price"]:''?>">
                    </div>
                </div>
                    <div class="tab-content">
                        <?php foreach($languages as $k => $language){ ?>
                            <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label><strong>Başlıq</strong></label><br/>
                                        <input class="form-control" type="text" name="title_<?= $language["name"] ?>" value="<?=$model?$model["title_".$language["name"]]:''?>">
                                    </div>
                                </div>

                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label><strong>Mətn</strong></label>
                                        <textarea class="summernote" name="text_<?= $language["name"] ?>"><?=$model?$model["text_".$language["name"]]:''?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>
            <?php if($model["cat"]>0){?>
            <div id="features_tab" class="features_tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">

                            <?php
                                $cat_array = Database::get()->selectOne('SELECT `template` FROM `categories` WHERE `id`='.$model["cat"]);

                                $feature_template = Database::get()->selectOne('SELECT `name`,`feature_groups` FROM `feature_templates` WHERE `id`='.$cat_array["template"]);
                                $feature_group_array = json_decode($feature_template['feature_groups']);

                                $features_array = json_decode($model["features"], true);
                                if(!empty($features_array)) {
                                    foreach ($features_array as $feature_group_id => $feature_array):
                                        foreach ($feature_array as $feature_id => $feature_value):
                                        $feature_key = 'feature-' .
                                            preg_replace('/group/', '', $feature_group_id) . '-' .
                                            preg_replace('/feature/','', $feature_id);
                                        $$feature_key = $feature_value;
                                        endforeach;
                                    endforeach;
                                }
                            ?>
                            <p><label><?=$lang->get('Feature template')?>:</label> <?=$feature_template["name"]?></p>

                            <?php foreach ($feature_group_array as $feature_group_id) :
                                $feature_group_sql = Database::get()->selectOne('SELECT `id`,`name`,`features` FROM `feature_groups` WHERE `id`='.$feature_group_id);
                                ?>

                            <label><strong><?=$feature_group_sql['name']?></strong></label><br/>
                            <?php $feature_array = json_decode($feature_group_sql['features']);

                                foreach ($feature_array as $feature_id):
                                    $feature_sql = Database::get()->selectOne('SELECT `id`,`name`,`type`,`select_options` FROM `features` WHERE `id`=' . $feature_id);
                                    ?>
                                    - <?=$feature_sql['name']?><br/>
                                    <?php


                                    $feature_key = 'feature-'.$feature_group_sql['id'].'-'.$feature_sql['id'];
                                    if($feature_sql['type']==2){
                                        ?>
                                        <select class="form-control" name="<?=$feature_key?>">
                                        <?php
                                        $select_options_array = json_decode( $feature_sql['select_options']);
                                        foreach($select_options_array as $row){;?>
                                            <option value="<?=$row;?>" <?=(isset($$feature_key) && $row==$$feature_key)?'selected':''?> ><?=$row;?></option>
                                        <?php } ?>
                                        </select><br/>
                                        <?php
                                    }elseif($feature_sql['type']==3){
                                        ?>
                                        <script>
                                            $(function () {
                                                <?php if(!empty($$feature_key)){?>
                                                $('.ui<?=$feature_key?>.dropdown').dropdown('set selected',<?=json_encode($$feature_key)?>);
                                                <?php }else{?>
                                                $('.ui<?=$feature_key?>.dropdown').dropdown();
                                                <?php }?>
                                            });
                                        </script>
                                        <select id="multiple-select" class=" ui<?=$feature_key?> fluid search normal dropdown" multiple name="<?=$feature_key.'[]'?>">
                                            <?php
                                            $select_options_array = json_decode( $feature_sql['select_options']);
                                            foreach($select_options_array as $row){;?>
                                                <option value="<?=$row;?>"><?=$row;?></option>
                                            <?php } ?>
                                        </select>
                                        <?php
                                    }else{
                                        ?>
                                        <input class="form-control" name="feature-<?=$feature_group_sql['id'].'-'.$feature_sql['id']?>" value="<?=isset($$feature_key)?$$feature_key:''?>" type="text"/><br/>
                                    <?php }?>
                                <?php endforeach;?>

                            <?php endforeach;?>



                        </div>
                    </div>
                </div>
            </div> <!--features_tab-->
            <?php }?>

            <?php $get_photos=[];?>
            <div id="photos_tab" class="photos_tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="image"><?=$lang->get('Photo')?></label>
                            <div class="form-group col-md-12">
                                <div class="step_wrapper upload_wrap">
                                    <br>
                                    <button type="button" class="btn lighten-1 z-depth-0 add_photo image_count_row" ><?=$lang->get('Add Photos')?></button>

                                    <input type="hidden" value="0" id="last_bar" />
                                    <input type="hidden" value="0" id="rotated" name="rotated" />
                                    <input type="hidden" value="0" id="edited" name="edited" />
                                    <input type="hidden" value="0" id="announce_id" name="announce_id" />
                                    <br><br>
                                    <ul class="photo_list" id="sortable">
                                        <?php
                                        $product_photos = $data['product_photos'];

                                        if(count($product_photos) > 0) {
                                            foreach($product_photos as $photos) {
                                                echo '<li data-photoid="'.$photos['id'].'">
								<div class="li_div">
									<div class="li_div2"><img class="uploading_image" src="/'.Url::uploadPath().$photos['thumb'].'" alt="" /></div>
								</div>
								<a href="javascript:void(0);" degrees="0" class="delete_photo delete_photo_new"></a>
								<a href="javascript:void(0);" class="turn_left turn_new rotate" ></a>
								<a href="javascript:void(0);" class="turn_right turn_new rotate" ></a>
								<br>
							</li>';
                                            }

                                        }
                                        ?>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
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


<form id="add_elan_form" action="../../photo_uploader/add/<?=$model["id"]?>" data-imageurl="/<?=Url::uploadPath().'product_photos/'?>">
    <input type="hidden" name="product_id" value="<?=$model["id"]?>">
    <input type="file" id="files" name="files[]" style="display: none;" accept="image/jpeg,image/png" multiple />
    <input type="submit" id="submit_form" class="hidden">
</form>

<span class="hidden delete_img_url" data-delimageurl="../../photo_uploader/delete/<?=$model["id"]?>"></span>
<span class="hidden rotate_img_url" data-rotateimageurl="../../photo_uploader/rotate/<?=$model["id"]?>'?>"></span>


