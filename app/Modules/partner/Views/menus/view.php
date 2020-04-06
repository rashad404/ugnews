<?php
use Models\LanguagesModel;
use Modules\partner\Models\MenusModel;

$params = $data["dataParams"];
$languages = LanguagesModel::getLanguages();
$defaultLang = LanguagesModel::getDefaultLanguage();
$menusModel = new MenusModel();
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="../index"><span style="color:#8bc34a;"><?= $params["cTitle"]; ?></span></a> / Bax</span>
    </div>
</section>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <div class="box-header">
                    <div class="col-xs-12">
                        <h3 class="box-title">Baxış</h3>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-xs-12 secimet">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="width: 100%;float: left"><strong>ID : <?= $data['result']['id'] ?></strong></label>
                                <label style="width: 100%;float: left"><strong>Başlıq : <?= $data['result']['title_az'] ?></strong></label>
                                <label style="width: 100%;float: left"><strong>Qısa mətn : <?= html_entity_decode($data['result']['text_az']) ?></strong></label>
                                <label style="width: 100%;float: left"><strong>Əsas kateqoriya : <?= $menusModel->getMenuName($data['result']['parent_id']) ?></strong></label>

                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <?php
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

