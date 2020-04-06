<?php
use Models\LanguagesModel;
use Models\CategoriesModel;
use Modules\partner\Models\MenusModel;

$languages = LanguagesModel::getLanguages();
$defaultLang = LanguagesModel::getDefaultLanguage();
$categories = CategoriesModel::getCategories();
$categories = MenusModel::getMenus();
$params = $data['dataParams'];
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
                                <label style="width: 100%;float: left"><strong>Yazı AZ: <?= $data['result']['text_az'] ?></strong></label>
                                <label style="width: 100%;float: left"><strong>Yazı RU: <?= $data['result']['text_ru'] ?></strong></label>
                                <label style="width: 100%;float: left"><strong>Yazı EN: <?= $data['result']['text_en'] ?></strong></label>
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

