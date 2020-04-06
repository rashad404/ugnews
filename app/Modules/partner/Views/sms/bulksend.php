<?php
use \Helpers\Csrf;
use Helpers\Date;
use Helpers\Format;
use Models\LanguagesModel;
$params = $data['params'];
$postData = $data['postData'];
$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="header_info">
        <a href="index"><?= $params["title"]; ?></a> / <span style="font-weight: bold"><?=$lng->get('Send BULK SMS')?></span><br/>
    </div>
    <div>

    </div>
</section>

<section class="content">
    <div class="col-md-12">

        <div class="half_box_with_title">
            <div class="half_box_title"><?=$lng->get('Send BULK SMS')?></div>


            <form action="" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="half_box_body custom_block default">
                        <select name="bulk_type" class="select2 form-control">
                            <?php foreach($data['bulk_options'] as $key => $val):?>
                                <option <?=$postData['guest_id']==$key?'selected':''?> value="<?=$key?>"><?=$val?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="half_box_body custom_block default">
                            <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />

                            <div class="form-group">
                                <textarea name="text" placeholder="<?=$lng->get('Start typing')?>"></textarea>
                            </div>
                            <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Send BULK SMS')?></button>
                    </div>
                </div>
            </div>
            </form>
        </div>

    </div>

</section><!-- /.content -->


<link rel="stylesheet" href="<?=\Helpers\Url::templatePartnerPath()?>css/select2.min.css" />
<script src="<?=\Helpers\Url::templatePartnerPath()?>js/select2.min.js"></script>

<script>
    $(".select2.form-control").select2( {
        placeholder: "---",
        allowClear: true
    } );
</script>