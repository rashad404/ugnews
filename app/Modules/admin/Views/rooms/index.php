<?php
use Helpers\Url;
use Helpers\OperationButtons;
use Modules\admin\Models\ApartmentsModel;
$params = $data["dataParams"];
$lng = $data['lng'];
$def_language = $data['def_language'];
?>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <div class="box-header">
                    <div class="col-xs-12">
                        <span><a href="../../apartments/index"><span style="color:#4e4e4e;"><?=$lng->get('Apartments')?></span></a> / <?= ApartmentsModel::getName($data['apt_id']); ?> <?= $params["cTitle"]; ?></span>
                    </div>
                </div>
                    <form action="" method="post">
                        <div class="form_box">
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><strong><?=$lng->get('Name')?>:</strong></label><br/>
                                        <input class="form-control admininput" type="text" placeholder="" name="name_<?=$def_language?>">
                                    </div>

                                    <div class="input-group">
                                        <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                                        <button type="submit" class="btn btn-success">
                                            <?=$lng->get('Add')?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                    </form>
            </div><!-- /.box -->
        </div>
    </div>

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to(MODULE_ADMIN."/".$params["cName"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <div class="dropdown secimet">
                            <a class="dropdown-toggle pointer secimetbtn" data-toggle="dropdown">
                                <span>Actions...<i class="fa fa-caret-down"></i></span>
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
                                <th><?=$lng->get('Name')?></th>
                                <?php if($params["cPositionEnable"]){ ?><th>Order</th><?php } ?>
                                <?php if($params["cStatusMode"]){ ?><th>Status</th><?php } ?>
                                <?php if($params["cCrudMode"]){ ?><th>Actions</th><?php } ?>
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
                                    <td class="admin-arrow-box">
                                        <?= $item["name"]?>
                                    </td>
                                    <?php $opButtons = new OperationButtons();?>
                                    <?php if($params["cPositionEnable"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getPositionIcons($item["id"],MODULE_ADMIN."/".$params["cName"])?></td>
                                    <?php } ?>
                                    <?php if($params["cStatusMode"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getStatusIcons($item["id"],$item["status"]); ?> </td>
                                    <?php } ?>
                                    <?php if($params["cCrudMode"]){ ?>
                                        <td class="admin-arrow-box">
                                            <a href="/<?=MODULE_ADMIN."/".$params["cName"]?>/delete/<?=$item["id"]?>" title="" data-toggle="tooltip" class="btn btn-xs btn-danger delete_confirm" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
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
    </div>
</section><!-- /.content -->