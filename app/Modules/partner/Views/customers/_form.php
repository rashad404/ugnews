<?php
use Models\LanguagesModel;

$item = $data["item"];
$lng = $data['lng'];
$languages = LanguagesModel::getLanguages('partner');
$defaultLanguage = LanguagesModel::getDefaultLanguage('partner');
?>
<script>
    $(function() {
        // $('.ui.dropdown').dropdown();
        $('.ui.dropdown').dropdown('set selected',<?=$features?>);
    });
</script>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form_box">
        <div class="row default">
            <?php $dp_c=$dtp_c=0; foreach ($data['input_list'] as $value) :?>
                <?php if(!empty($value['name'])):?>

                    <?php
                    if(!$item){
                        $input_value = '';
                        if($value['type']=='datetime'){
//                            $input_value = date('Y-m-d', time()).'T'.date('h:i', time());
                            $input_value = date('m/d/Y h:00 A');
//                            echo $input_value;
                        }
                        if($value['type']=='date'){
                            $input_value = date('m/d/Y');
                        }
                    }else{
                        $input_value = $item[$value['key']];
                    }?>
                    <div class="col-sm-<?=($value['type']=='textarea')?'12':'4'?>">
                        <div class="form-group">
                            <label><strong><?=$lng->get($value['name'])?>:</strong></label><br/>
                            <?php if($value['type']=='select_box'):?>
                                <select name="<?=$value['key']?>" class="form-control ">
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
                                <textarea id="summernote" name="<?=$value['key']?>"><?=$input_value?></textarea>

                            <?php elseif($value['type']=='datetime'):?>
                                <div class="form-group default_date">
                                    <div class='input-group date' id='datetimepicker<?=$dp_c?>'>
                                        <input type='text' name="<?=$value['key']?>" value="<?=$input_value?>"/>
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <?php  $dp_c++;?>

                            <?php elseif($value['type']=='date'):?>
                                <div class="form-group default_date">
                                    <div class="input-group date" id="datepicker<?=$dtp_c?>">
                                        <input value="<?=$input_value?>" name="<?=$value['key']?>" type="text">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <?php  $dtp_c++;?>

                            <?php else: ?>
                                <input class="form-control admininput" type="<?=$value['type']?>" placeholder="" name="<?=$value['key']?>" value="<?=$input_value?>">
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
                    <?=$lng->get('Save')?>
                </button>
            </div>
            <div class="input-group pull-right">
                <div class="pos-rel-top-6 ">
                    <label class="padyan15"><?=$lng->get('Status')?></label>
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
