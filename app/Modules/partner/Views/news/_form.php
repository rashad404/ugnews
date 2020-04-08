<?php
use Models\LanguagesModel;

$item = $data["item"];
$lng = $data['lng'];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();
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
            <div class="col-sm-3">
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
                        <?php if(!empty($item['image'])): ?>
                            <img src="<?=\Helpers\Url::filePath().$item['image']?>">
                        <?php endif; ?>
                        <input type="file" name="image[]"  value="<?= empty($item['image']) ? '' : \Helpers\Url::filePath().$item['image'] ?>" />
                    </div>
                </div>
            </div>

            <?php foreach ($data['input_list'] as $value) :?>
                <?php if(!empty($value['name'])):?>
                    <?php if($value['key']=='notice_date'):?>
                        </div><div class="row">
                    <?php endif;?>
                    <div class="col-sm-<?=($value['type']=='textarea')?'12':'4'?>">
                        <div class="form-group">
                            <label><strong><?=$lng->get($value['name'])?>:</strong></label><br/>
                            <?php if($value['type']=='select_box'):?>
                                <select name="<?=$value['key']?>" class="form-control ">
                                    <option value="0" <?=$item?'':'selected'?>><?=$lng->get('Not tenant')?></option>
                                    <?php foreach($value['data'] as $data):?>
                                        <option <?=$item&&$item[$value['key']]==$data['key']?'selected':''?> value="<?=$data['key']?>" <?=$data['disabled']?>><?=$data['name']?></option>
                                    <?php endforeach;?>
                                </select>
                            <?php elseif($value['type']=='select2'):?>
                                <select name="<?=$value['key']?>" class="select2 form-control">
                                    <?php foreach($value['data'] as $data):?>
                                        <option <?=$item&&$item[$value['key']]==$data['key']?'selected':''?> value="<?=$data['key']?>" <?=$data['disabled']?>><?=$data['name']?></option>
                                    <?php endforeach;?>
                                </select>
                            <?php elseif($value['type']=='textarea'):?>
                                <textarea id="summernote" name="<?=$value['key']?>"><?=$item[$value['key']]?></textarea>
                            <?php elseif($value['type']=='tags'):?>
                                <input class="tags_input" value="<?=$item[$value['key']]?>" data-role="tagsinput" type="text" name="<?=$value['key']?>"/><br/><br/>
                            <?php else: ?>
                                <input class="form-control admininput" type="<?=$value['type']?>" placeholder="" name="<?=$value['key']?>" value="<?=$item?$item[$value['key']]:''?>">
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endif;?>
            <?php endforeach;?>

        </div>

    </div>

    <div class="box-footer">
        <div class="col-xs-12">
            <div class="input-group pull-left">
                <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                <button type="submit" class="btn btncolor secimetbtnadd">
                    Save
                </button>
            </div>
            <div class="input-group pull-right">
                <div class="pos-rel-top-6 ">
                    <label class="padyan15">Active</label>
                    <input class="admin-switch" data-on-text="" data-off-text="" id="status" type="checkbox" name="status" value="1" <?php if($item && $item["status"]==0) echo ""; else echo "checked";?>>
                </div>
            </div>
        </div>
    </div>
</form>


<link rel="stylesheet" href="<?=\Helpers\Url::templatePartnerPath()?>css/select2.min.css" />
<script src="<?=\Helpers\Url::templatePartnerPath()?>js/select2.min.js"></script>


<link rel="stylesheet" href="<?=\Helpers\Url::templatePartnerPath()?>assets/datepicker/bootstrap-datetimepicker.min.css" />
<script src="<?=\Helpers\Url::templatePartnerPath()?>assets/datepicker/bootstrap-datetimepicker.min.js"></script>

<script>
    $(".select2.form-control").select2( {
        placeholder: "---",
        allowClear: true
    } );
</script>

<script>
    $(document).ready(function() {
        $(function() {
            <?php for ($i=0;$i<$dp_c;$i++):?>
            $('#datetimepicker<?=$i?>').datetimepicker();
            <?php endfor;?>
            <?php for ($i=0;$i<$dtp_c;$i++):?>
            $('#datepicker<?=$i?>').datetimepicker({
                format: 'L'
            });
            <?php endfor;?>

        });
    });
</script>
