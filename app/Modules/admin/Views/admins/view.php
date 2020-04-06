<?php
use \Helpers\OperationButtons;
use Models\LanguagesModel;
use Models\CategoriesModel;

$languages = LanguagesModel::getLanguages();
$defaultLang = LanguagesModel::getDefaultLanguage();
$categories = CategoriesModel::getCategories();
$params = $data["dataParams"];
$level = $params["level"];
?>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3>Admin məlumatlarına bax</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content form-content">
                            <h5><b>ID:</b> <?= $data['result']['id'] ?></h5>
                            <h5><b>Login:</b> <?= $data['result']['login'] ?></h5>
                            <h5><b>E-mail:</b> <?= $data['result']['email'] ?></h5>
                            <h5><b>Ad-familya:</b> <?= (!empty($data['result']['name'])) ? $data['result']['name'] : 'Qeyd olunmayıb' ?></h5>
                            <h5><b>Level:</b> <?=$level[$data['result']['role']]?></h5>
                            <hr>

                            <h5><b>Status:</b> <?= ($data['result']['status']==0) ? $status='Deaktivdir' : $status='Aktivdir'; ?></h5>

                        </div>
                    </div>

                </div>
                <!-- /.row (nested) -->

                <?php
                if($data["issetAlbum"])
                    include dirname(__DIR__)."/photos/_photos.php";
                ?>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
</div>

