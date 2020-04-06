<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use Models\LanguagesModel;
$params = $data['params'];

$defaultLang = LanguagesModel::getDefaultLanguage();
$texts = \Modules\admin\Models\TextsModel::getTexts();
?>
<!-- Content Header (Page header) -->
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
                                <span>Seçim edin<i class="fa fa-caret-down"></i></span>
                            </a>
                            <a href="<?php echo Url::to(MODULE_ADMIN."/".$params["name"]."/create")?>" class="btn btncolor secimetbtnadd">
                                Əlavə et
                                <i class="fa fa-plus afa"></i>
                            </a>
                            <ul class="dropdown-menu top-40">
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.acbtnhid').click()">Aktiv et</a>
                                    <input type="submit" class="hidden acbtnhid" name="active" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.deacbtnhid').click()">Deaktiv et</a>
                                    <input type="submit" class="hidden deacbtnhid" name="deactive" value="1">
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
                                <th>Ad</th>
                                <th>Sıralama</th>
                                <th>Aktivlik</th>
                                <th>Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $row){ ?>
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
                                    <td class="admin-arrow-box"><?= $row["name"]?></td>

                                    <?php $opButtons = new OperationButtons();?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getPositionIcons($row["id"],MODULE_ADMIN."/".$params["name"])?></td>
                                        <td class="admin-arrow-box"> <?= $opButtons->getStatusIcons($row["id"],$row["status"]); ?> </td>
                                        <td class="admin-arrow-box"> <?= $opButtons->getCrudIconsEditDel($row["id"],MODULE_ADMIN."/".$params["name"])?> </td>
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