<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use \Helpers\Pagination;
use Models\LanguagesModel;
$params = $data['dataParams'];

$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><?= $params["cTitle"]; ?></span>
    </div>
</section>

<section class="content">

    <?php include "_search.php"; ?>

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to(MODULE_ADMIN."/".$params["cName"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <div class="dropdown secimet">
                            <a class="dropdown-toggle pointer secimetbtn" data-toggle="dropdown">
                                <span>Actions...<i class="fa fa-caret-down"></i></span>
                            </a>
                            <a href="<?php echo Url::to(MODULE_ADMIN."/".$params["cName"]."/create")?>" class="btn btncolor secimetbtnadd">
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
                                <th>Title</th>
                                <?php if($params["cPositionEnable"]){ ?><th>Order</th><?php } ?>
                                <?php if($params["cStatusMode"]){ ?><th>Active</th><?php } ?>
                                <?php if($params["cCrudMode"]){ ?><th>Actions</th><?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["rows"]  as $row){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20">
                                        <div class="checkboxum">
                                            <label>
                                                <input type="checkbox" name="row_check[]" value="<?= $row["id"]; ?>">
                                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="admin-arrow-box width-20"><?= $row["id"]?></td>
                                    <td class="admin-arrow-box">
                                        <?= $row["name"]?><br/>
                                        <span style="color: #a4a4a4;"><?= $row["address"]?></span>
                                    </td>
                                    <?php $opButtons = new OperationButtons();?>
                                    <?php if($params["cPositionEnable"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getPositionIcons($row["id"],MODULE_ADMIN."/".$params["cName"])?></td>
                                    <?php } ?>
                                    <?php if($params["cStatusMode"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getStatusIcons($row["id"],$row["status"]); ?> </td>
                                    <?php } ?>
                                    <?php if($params["cCrudMode"]){ ?>
                                        <td class="admin-arrow-box">
                                            <a href="/<?=MODULE_ADMIN?>/prices/index/<?=$row["id"]?>" title="" data-toggle="tooltip" class="btn btn-xs btn-primary" data-original-title="Prices">
                                                <i class="fa fa-dollar" style="padding:0 2px;"></i>
                                            </a>
                                            <a href="/<?=MODULE_ADMIN?>/rooms/index/<?=$row["id"]?>" title="" data-toggle="tooltip" class="btn btn-xs btn-primary" data-original-title="Rooms">
                                                <i class="fa fa-bars"></i>
                                            </a>
                                            <a href="/<?=MODULE_ADMIN?>/beds/index/<?=$row["id"]?>" title="" data-toggle="tooltip" class="btn btn-xs btn-primary" data-original-title="Beds">
                                                <i class="fa fa-bed"></i>
                                            </a>
                                            <a href="/<?=MODULE_ADMIN?>/parkings/apartment/<?=$row["id"]?>" title="" data-toggle="tooltip" class="btn btn-xs btn-primary" data-original-title="Parkings">
                                                <i class="fa fa-car"></i>
                                            </a>
                                            <a href="/<?=MODULE_ADMIN."/".$params["cName"]?>/album/<?=$row["id"]?>" title="" data-toggle="tooltip" class="btn btn-xs btn-primary" data-original-title="Photos">
                                                <i class="fa fa-image"></i>
                                            </a>
                                            <?= $opButtons->getCrudIconsEditDel($row["id"],MODULE_ADMIN."/".$params["cName"])?>
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