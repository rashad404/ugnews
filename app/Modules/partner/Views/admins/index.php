<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use \Helpers\Pagination;
use Models\LanguagesModel;
$params = $data['dataParams'];

$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">
            <?= $params["cTitle"]; ?>
        </h3>

        <form action="<?php echo Url::to(MODULE_PARTNER."/".$params["cName"]."/operation")?>" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="pull-left padding-top-7 text-danger margin-right-10">
                        <b>Seçilmişləri: </b>
                    </p>

                    <button type="submit" name="delete" value="1" class="btn btn-sm btn-danger  pull-left margin-right-10 delete_confirm"><i class="fa fa-times"></i> Sil</button>
                    <button type="submit" name="active" value="1" class="btn btn-sm btn-info  pull-left margin-right-10"><i class="fa fa-check"></i> Aktiv et</button>
                    <button type="submit" name="deactive" value="1" class="btn btn-sm btn-warning pull-left margin-right-10"><i class="fa fa-ban"></i> Deaktiv et</button>

                    <a href="<?php echo Url::to(MODULE_PARTNER."/".$params["cName"]."/create")?>" class="btn  btn-sm btn-success pull-right margin-right-10"><i class="fa fa-plus"></i> Əlavə et</a>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="width-20"><input type="checkbox" class="all-check"></th>
                                <th class="width-20">#</th>
                                <th>Login</th>
                                <th>Ad Soyad</th>
                                <th>E-mail</th>
                                <th>Səviyyə</th>
                                <?php if($params["cPositionEnable"]){ ?><th class="width-20">Sıralama</th><?php } ?>
                                <?php if($params["cStatusMode"]){ ?><th class="width-20">Aktiv</th><?php } ?>
                                <?php if($params["cCrudMode"]){ ?><th class="width-20">Əməliyyatlar</th><?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["rows"]  as $row){?>
                                <tr>
                                    <td class="width-20"><input type="checkbox" name="row_check[]" value="<?= $row["id"]; ?>"></td>
                                    <td><?= $row["id"]?></td>
                                    <td><?= $row["login"]?></td>
                                    <td><?=$row["name"]?>
                                    <td><?=$row["email"]?>
                                    <td><?=$params["level"][$row['role']]?></td>
                                    <?php if($params["cPositionEnable"]){ ?>
                                        <td> <?= OperationButtons::getPositionIcons($row["id"],MODULE_PARTNER."/".$params["cName"])?></td>
                                    <?php } ?>
                                    <?php if($params["cStatusMode"]){ ?>
                                        <td> <?= OperationButtons::getStatusIcons($row["id"],$row["status"]); ?> </td>
                                    <?php } ?>
                                    <?php if($params["cCrudMode"]){ ?>
                                        <td> <?= OperationButtons::getCrudIcons($row["id"],MODULE_PARTNER."/".$params["cName"])?> </td>
                                    <?php } ?>

                                </tr>
                            <?php } ?>


                            </tbody>
                        </table>

                    <div class="clearfix"></div>
                </div>


                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
    </div>
    </form>
</div>
</div>