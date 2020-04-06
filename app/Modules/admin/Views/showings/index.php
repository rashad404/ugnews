<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use Models\LanguagesModel;
use Modules\admin\Models\ApartmentsModel;
use Modules\admin\Models\CustomersModel;
use Modules\admin\Models\ShowingsModel;
use Modules\admin\Models\TenantsModel;

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

    <?php include "_search.php"; ?>

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to(MODULE_ADMIN."/".$params["name"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <div class="dropdown secimet">
                            <a class="dropdown-toggle pointer secimetbtn" data-toggle="dropdown">
                                <span>Actions...<i class="fa fa-caret-down"></i></span>
                            </a>
                            <a href="<?php echo Url::to(MODULE_ADMIN."/".$params["name"]."/add")?>" class="btn btncolor secimetbtnadd">
                                Add
                                <i class="fa fa-plus afa"></i>
                            </a>
                            <ul class="dropdown-menu top-40">
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.acbtnhid').click()">Activate</a>
                                    <input type="submit" class="hidden acbtnhid" name="active" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.deacbtnhid').click()">Deactivate</a>
                                    <input type="submit" class="hidden deacbtnhid" name="deactive" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.delbtnhid').click()">Delete</a>
                                    <input type="submit" class="hidden delbtnhid" name="delete" value="1">
                                </li>
                            </ul>
                        </div>
                        <table id="datatable2" class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th class="width-20">
                                    <div class="checkboxum">
                                        <label>
                                            <input type="checkbox" class="all-check">
                                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                        </label>
                                    </div>
                                </th>
                                <th class="width-20">#</th>
                                <?php foreach ($data['inputs'] as $item):?>
                                    <?php if($item['index']):?><th><?=$item['name']?></th><?php endif;?>
                                <?php endforeach;?>
                                <?php if($params["position"]){ ?><th><?=$lng->get('Order')?></th><?php } ?>
                                <?php if($params["status"]){ ?><th><?=$lng->get('Status')?></th><?php } ?>
                                <?php if($params["actions"]){ ?><th><?=$lng->get('Actions')?></th><?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $item){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20">
                                        <div class="checkboxum">
                                            <label>
                                                <input type="checkbox" name="row_check[]" value="<?= $item["id"]; ?>">
                                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="admin-arrow-box width-20"><?= $item["id"]?></td>
                                    <?php foreach ($data['inputs'] as $input_item):?>
                                        <?php if($input_item['index']):?>
                                            <?php
                                            if($input_item['type']=='datetime-local'){
                                                $input_value = date('m/d/Y h:i A', $item[$input_item['key']]);
                                            }else{
                                                $input_value = $item[$input_item['key']];
                                            }
                                            if($input_item['key']=='apt_id'){
                                                $input_value = ApartmentsModel::getName($input_value);
                                            }elseif($input_item['key']=='guest_id'){
                                                if(!empty($input_value)){
                                                    $input_value = CustomersModel::getName($input_value);
                                                }elseif(!empty($item['tenant_id'])){
                                                    $input_value = TenantsModel::getName($item['tenant_id']);
                                                }
                                            }elseif($input_item['key']=='date'){
                                                $input_value = date("M d", strtotime($input_value));
                                            }elseif($input_item['key']=='time'){
                                                if(empty($input_value)){
                                                    $input_value='All Day';
                                                }
                                                else {
                                                    $input_value = date("g:i A", strtotime($input_value));
                                                }
                                            }elseif($input_item['key']=='type'){
                                                $input_value = ShowingsModel::getTypes($input_value);
                                            }
                                            ?>
                                            <td class="admin-arrow-box"><?= $input_value?></td>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    <?php $opButtons = new OperationButtons();?>
                                    <?php if($params["position"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getPositionIcons($item["id"],MODULE_ADMIN."/".$params["name"])?></td>
                                    <?php } ?>
                                    <?php if($params["status"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getStatusIcons($item["id"],$item["status"]); ?> </td>
                                    <?php } ?>
                                    <?php if($params["actions"]){ ?>
                                        <td class="admin-arrow-box">
                                            <?= $opButtons->getCrudIconsEditDel($item["id"],MODULE_ADMIN."/".$params["name"])?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->