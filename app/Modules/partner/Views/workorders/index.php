<?php

use Helpers\Format;
use \Helpers\OperationButtons;
use Models\LanguagesModel;
use Modules\partner\Models\TenantsModel;
use Modules\partner\Models\WorkordersModel;
$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">

                            <div class="table-responsive">
                                <table id="datatable2" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?=$lng->get('Status')?></th>
                                        <th><?=$lng->get('Tenant')?></th>
                                        <th><?=$lng->get('Category')?></th>
                                        <th><?=$lng->get('Location')?></th>
                                        <th><?=$lng->get('Requested')?></th>
                                        <th><?=$lng->get('Description')?></th>
                                        <th><?=$lng->get('Date Completed')?></th>
                                        <th><?=$lng->get('Actions')?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($data["list"]  as $item){ ?>
                                        <tr>
                                            <td class="admin-arrow-box width-20"><?=$item['id']?></td>
                                            <td>
                                                <?php
                                                    if($item['status']==0){
                                                        $status_color='red';
                                                    }elseif($item['status']==1){
                                                        $status_color='rgb(145, 79, 176)';
                                                    }elseif($item['status']==2){
                                                        $status_color='orange';
                                                    }elseif($item['status']==3){
                                                        $status_color='gray';
                                                    }elseif($item['status']==4){
                                                        $status_color='green';
                                                    }else{
                                                        $status_color='black';
                                                    }
                                                ?>
                                                <span style="color:<?=$status_color?>;font-weight: bold"><?=WorkordersModel::getStatus($item['status'])?></span>
                                            </td>
                                            <td class="admin-arrow-box width-20">
                                                <?php
                                                    $tenant_info = TenantsModel::getItem($item['user_id']);
                                                    $item['first_name'] = $tenant_info['first_name'];
                                                    $item['last_name'] = $tenant_info['last_name'];
                                                ?>
                                                <a href="../tenants/view/<?=$item['user_id']?>"><?= $item['first_name'].' '.$item['last_name']?></a>
                                            </td>
                                            <td class="admin-arrow-box"><?=WorkordersModel::getCategories($item['category'])?></td>
                                            <td class="admin-arrow-box"><?=WorkordersModel::getLocations($item['location'])?></td>
                                            <td><?=$item['date']?></td>
                                            <td><?=Format::getText($item['text'], 50)?></td>
                                            <td><?=($item['date_completed']!='0000-00-00')?$item['date_completed']:''?></td>
                                            <?php $opButtons = new OperationButtons();?>
                                            <?php if($params["actions"]){ ?>
                                                <td class="admin-arrow-box">
                                                    <?= $opButtons->getCrudIcons($item["id"],MODULE_PARTNER."/".$params["name"])?>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script>
    function copyFunction() {
        const $temp = $("<input>");
        $("body").append($temp);
        const copyText = event.target.innerHTML;
        $temp.val(copyText).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied: '+copyText);
    }
</script>