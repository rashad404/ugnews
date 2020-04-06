<?php
use \Helpers\OperationButtons;
use Models\LanguagesModel;
use Models\CategoriesModel;

$languages = LanguagesModel::getLanguages();
$defaultLang = LanguagesModel::getDefaultLanguage();
$categories = CategoriesModel::getCategories();
$params = $data['dataParams'];
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><?= $params["cTitle"]; ?></span>
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
                                <label style="width: 100%;float: left"><strong>Youtube URL: <?= $data['result']['title_az'] ?></strong></label>
                                <?php if($data['result']['status']==0){$status='Deaktivdir';}else{$status='Aktivdir';} ?>
                                <label style="width: 100%;float: left"><strong>ID : <?= $data['result']['id'] ?></strong></label>
                                <label style="width: 100%;float: left"><strong>Status : <?= $status ?></strong></label>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <?php
                //                if($data["issetAlbum"])
                //                    include dirname(__DIR__)."/photos/_photos.php";
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->