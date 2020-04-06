<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use \Helpers\Pagination;
use Models\LanguagesModel;
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
                                <th>Name</th>
                                <th>Available Date</th>
                                <?php if($params["position"]){ ?><th>Order</th><?php } ?>
                                <?php if($params["status"]){ ?><th>Active</th><?php } ?>
                                <?php if($params["actions"]){ ?><th>Actions</th><?php } ?>
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
                                        <?= $item["first_name"]?> <?= $item["last_name"]?><br/>
                                        <div class="list_alt_text">
                                            <i class="fa fa-phone"></i> <?= $item["phone"]?> <i class="fa fa-envelope"></i> <?= $item["email"]?>
                                        </div>
                                    </td>
                                    <td class="admin-arrow-box"><?= date('m/d/Y g:i A', $item["app_time"])?></td>

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