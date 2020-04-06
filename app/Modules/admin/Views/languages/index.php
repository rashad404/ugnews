<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use \Helpers\Pagination;
use Models\LanguagesModel;
$params = $data['dataParams'];
$pagination = $data["pagination"];
?>

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">
            <?= $params["cTitle"]; ?>
        </h3>
        <form action="<?php echo Url::to(MODULE_ADMIN."/".$params["cName"]."/operation")?>" method="post">
            <div class="panel panel-default">
            <div class="panel-heading">
                <p class="pull-left padding-top-7 text-danger margin-right-10">
                    <b>Seçilmişləri: </b>
                </p>

                <button type="submit" name="delete" value="1" class="btn btn-sm btn-danger search-box-link pull-left margin-right-10 delete_confirm"><i class="fa fa-times"></i> Sil</button>
                <button type="submit" name="active" value="1" class="btn btn-sm btn-info search-box-link pull-left margin-right-10"><i class="fa fa-check"></i> Aktiv et</button>
                <button type="submit" name="deactive" value="1" class="btn btn-sm btn-warning search-box-link pull-left margin-right-10"><i class="fa fa-ban"></i> Deaktiv et</button>
                <?php if(\Helpers\Session::get('auth_session_role') == 1){ ?><a href="<?php echo Url::to(MODULE_ADMIN."/".$params["cName"]."/create")?>" class="btn  btn-sm btn-success pull-right margin-right-10"><i class="fa fa-plus"></i> Əlavə et</a><?php } ?>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="margin-bottom-10">
                    <label class="pull-left margin-top-6 margin-right-10">Əsas dili təyin et:</label>
                    <select onChange="window.location.href=this.value" class="form-control" style="width:auto;">
                        <?php
                        $languages = LanguagesModel::getLanguages();
                        foreach($languages as $lang){
                            if($lang["default"]==1) $selected='selected="selected"'; else $selected='';
                            echo '<option value="'.Url::to(MODULE_ADMIN.'/'.$params["cName"].'/setdefaultlanguage/'.$lang["id"]).'" '.$selected.'>'.$lang["fullname"].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="width-20"><input type="checkbox" class="all-check"></th>
                            <th class="width-20">#</th>
                            <th>Ad</th>
                            <?php if($params["cPositionEnable"]){ ?><th class="width-20">Sıralama</th><?php } ?>
                            <?php if($params["cStatusMode"]){ ?><th class="width-20">Aktiv</th><?php } ?>
                            <?php if($params["cCrudMode"] && \Helpers\Session::get('auth_session_role') == 1){ ?><th class="width-20">Əməliyyatlar</th><?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data["rows"]  as $row){ ?>
                            <tr>
                                <td class="width-20"><input type="checkbox" name="row_check[]" value="<?= $row["id"]; ?>"></td>
                                <td><?= $row["id"]?></td>
                                <td>
                                    <img src="<?= Url::templateModulePath('admin')."images/flags/".$row["flag"]?>" width="20" height="20"/>
                                      <?= $row["fullname"]?>
                                </td>


                                <?php if($params["cPositionEnable"]){ ?>
                                    <td> <?= OperationButtons::getPositionIcons($row["id"],MODULE_ADMIN."/".$params["cName"])?></td>
                                <?php } ?>
                                <?php if($params["cStatusMode"]){ ?>
                                    <td> <?= OperationButtons::getStatusIcons($row["id"],$row["status"],$row["default"]); ?> </td>
                                <?php } ?>
                                <?php if($params["cCrudMode"] && \Helpers\Session::get('auth_session_role') == 1){ ?>
                                    <td> <?= OperationButtons::getCrudIcons($row["id"],MODULE_ADMIN."/".$params["cName"])?> </td>
                                <?php } ?>


                            </tr>
                        <?php } ?>


                        </tbody>
                    </table>
                    <span class="text-info">
                        <?=Pagination::getCountData($pagination->countRows,$pagination->startRow,$pagination->limitRow);?>
                    </span>
                    <span class="pull-right">
                        <?= $pagination->getLimitSelector()?>
                    </span>
                    <div class="clearfix"></div>
                </div>
                <nav class="text-center">
                    <?= $pagination->pageNavigation();?>
                </nav>

                    <div class="clearfix"></div>
                </div>


                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        </form>
    </div>
</div>