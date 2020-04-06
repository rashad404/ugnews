<?php
use Models\LanguagesModel;
use Helpers\Format;
use Modules\partner\Models\WorkordersModel;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\BedsModel;
$params = $data['params'];
$item = $data['item'];
$user_info = $data['user_info'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="header_info">
        <a href="../index"><?=$params['title']?></a> / <span style="font-weight: bold"><a href="../../tenants/view/<?=$user_info['id']?>"><?= $user_info['first_name'].' '.$user_info['last_name']?></a></span><br/>
    </div>
    <div>

    </div>
</section>

<section class="content">
    <div class="col-lg-10 col-md-12">

        <div class="row">

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Details')?> #<?=$item['id']?></div>
                    <div class="half_box_body">
                        <ul>
                            <form action="" method="post">
                                <table class="default_vertical">
                                <tr>
                                    <td><?=$lng->get('Apartment')?>:</td>
                                    <td>
                                        <?=ApartmentsModel::getName($item["apt_id"])?>,
                                        <?=RoomsModel::getName($item["room_id"])?>
                                        <?=BedsModel::getName($item["bed_id"])?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Category')?>:</td>
                                    <td><?=WorkordersModel::getCategories($item["category"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Location')?>:</td>
                                    <td><?=WorkordersModel::getLocations($item["location"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Date')?>:</td>
                                    <td><?=$item["date"]?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Urgent')?>:</td>
                                    <td><?=WorkordersModel::getUrgentOptions($item["urgent"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Safety alert')?>:</td>
                                    <td><?=WorkordersModel::getSafetyOptions($item["safety"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Permission')?>:</td>
                                    <td><?=WorkordersModel::getPermissionOptions($item["permission"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Date Completed')?>:</td>
                                    <td><?=($item["date_completed"]>0)?$item["date_completed"]:'-'?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Description')?>:</td>
                                    <td><?=$item["text"]?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Status')?>:</td>
                                    <td>
                                        <div style="padding-top: 20px;">
                                            <select style="width: 200px;" name="status" class="select2 form-control">
                                                <?php foreach(WorkordersModel::getStatus() as $list):?>
                                                <option <?=$item['status']==$list['key']?'selected':''?> value="<?=$list['key']?>" <?=$list['disabled']?>><?=$list['name']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                                        <button style="margin-top: 20px;" class="btn default_button btn-lg" name="submit" type="submit"><?=$lng->get('Update')?></button>
                                    </td>
                                </tr>
                            </table>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>




        </div>


    </div>

</section><!-- /.content -->

<link rel="stylesheet" href="<?=\Helpers\Url::templateModulePath()?>css/select2.min.css" />
<script src="<?=\Helpers\Url::templateModulePath()?>js/select2.min.js"></script>
<script>
    $(".select2.form-control").select2( {
        placeholder: "---",
        allowClear: true
    } );
</script>