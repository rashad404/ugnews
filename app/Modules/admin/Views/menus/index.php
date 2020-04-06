<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
$params = $data['dataParams'];
$defaultLang = $params['defaultLang'];
$lang = $params['lang'];
$menusModel = $data['menusModel'];
$operationButtons = $data['operationButtons'];

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
                                <span><?=$lang->get('Choose')?><i class="fa fa-caret-down"></i></span>
                            </a>
                            <a href="<?php echo Url::to(MODULE_ADMIN."/".$params["cName"]."/create")?>" class="btn btncolor secimetbtnadd">
	                            <?=$lang->get('Add')?>
                                <i class="fa fa-plus afa"></i>
                            </a>
                            <ul class="dropdown-menu top-40">
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.acbtnhid').click()"><?=$lang->get('Activate')?></a>
                                    <input type="submit" class="hidden acbtnhid" name="active" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.deacbtnhid').click()"><?=$lang->get('Deactivate')?></a>
                                    <input type="submit" class="hidden deacbtnhid" name="deactive" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.delbtnhid').click()"><?=$lang->get('Delete')?></a>
                                    <input type="submit" class="hidden delbtnhid" name="delete" value="1">
                                </li>
                            </ul>
                        </div>
                        <table class="table table-striped table-responsive">
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
                                <th><?=$lang->get('Name')?></th>
                                <th><?=$lang->get('Place')?></th>
                                <th><?=$lang->get('URL')?></th>
                                <th><?=$lang->get('Main menu')?></th>
                                <?php if($params["cPositionEnable"]){ ?><th><?=$lang->get('Order')?></th><?php } ?>
                                <?php if($params["cStatusMode"]){ ?><th><?=$lang->get('Status')?></th><?php } ?>
                                <?php if($params["cCrudMode"]){ ?><th><?=$lang->get('Operations')?></th><?php } ?>
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
                                    <td class="admin-arrow-box"><?= $row["title_".$defaultLang]?></td>
                                    <td class="admin-arrow-box">
                                        <?php
                                        if($row["up"] == 0 && $row["down"] == 0) {
                                            echo '-';
                                        } else {
                                            if($row["up"] > 0) {
                                                echo $lang->get('Top').' ';
                                            }

                                            if($row["down"] > 0) {
	                                            echo $lang->get('Bottom').' ';
                                            }

                                        }
                                        ?></td>
                                    <td class="admin-arrow-box">
                                        <?php
                                        if(\Helpers\Security::filterUrl($row['url'])) {
                                            echo '<a href="'.$row['url'].'">'.$row['url'].'</a>';
                                        } else {
                                            if($row['menu_type'] == 'static') {
                                                echo '<a href="'.SITE_URL.$params['staticUrl'].'/'.$row['id'].'/'.$row['url'].'">'.SITE_URL.$params['staticUrl'].'/'.$row['id'].'/'.$row['url'].'</a>';
                                            } elseif($row['menu_type'] == 'site') {
                                                echo '<a href="'.SITE_URL.'/'.$row['url'].'">'.SITE_URL.'/'.$row['url'].'</a>';
                                            } else {
                                                echo '<a href="'.SITE_URL.$row['url'].'">'.SITE_URL.$row['url'].'</a>';
                                            }

                                        }
                                        ?></td>
                                    <td class="admin-arrow-box">

                                    <?php if($params["cPositionEnable"]){ ?>
                                        <td class="admin-arrow-box"> <?= $operationButtons::getPositionIcons($row["id"],MODULE_ADMIN."/".$params["cName"])?></td>
                                    <?php } ?>
                                    <?php if($params["cStatusMode"]){ ?>
                                        <td class="admin-arrow-box"> <?= $operationButtons::getStatusIcons($row["id"],$row["status"]); ?> </td>
                                    <?php } ?>
                                    <?php if($params["cCrudMode"]){ ?>
                                        <td class="admin-arrow-box"> <?= $operationButtons::getCrudIcons($row["id"],MODULE_ADMIN."/".$params["cName"])?> </td>
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