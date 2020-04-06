<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
$params = $data['dataParams'];
$defaultLang = $params['defaultLang'];
$lang = $params['lang'];
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
                                <th><?=$lang->get('Phone')?></th>
                                <th><?=$lang->get('E-mail')?></th>
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
                                    <td class="admin-arrow-box"><?= $row["name"].' '.$row["surname"].' '.$row["father_name"]?></td>
                                    <td class="admin-arrow-box"><?= $row["phone"]?></td>
                                    <td class="admin-arrow-box"><?= $row["email"]?></td>
                                    <td class="admin-arrow-box"><?= date("d-m-Y H:i", $row["time"])?></td>


                                    <?php if($params["cStatusMode"]){ ?>
                                        <td class="admin-arrow-box"> <?= $operationButtons::getStatusIcons($row["id"],$row["status"]); ?> </td>
                                    <?php } ?>
                                    <?php if($row['send']==0){?>
                                        <td class="admin-arrow-box"> <a href="send/<?=$row["id"]?>"  class="emelbtn pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Send"><img src="<?=Url::templateModulePath()?>/icons/send-icon.png" alt="Send"/></a> </td>
                                    <?php } ?>
                                    <?php if($params["cCrudMode"]){ ?>
                                        <td class="admin-arrow-box"> <?= $operationButtons::getCrudIcons($row["id"],MODULE_ADMIN."/".$params["urlName"])?> </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php echo $data["pagination"]->pageNavigation('pagination')?>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->