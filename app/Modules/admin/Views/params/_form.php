<?php
use Models\LanguagesModel;

$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();

include 'app/Core/ParamsInclude.php';
?>


<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12 secimet tab-content">

            <?php foreach ($params_list as $key=>$value): ?>
            <div class="col-sm-12">
                <div class="form-group">
                    <label><strong><?=ucfirst(strtolower($key))?></strong></label>
                    <input type="text" name="<?=$key?>" value="<?=$value?>" class="form-control admininput">
                </div>
            </div>
            <?php endforeach;?>

        </div>
    </div><!-- /.box-body -->
    <div class="box-footer">
        <div class="col-xs-12">
            <div class="input-group pull-left">
                <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                <button type="submit" name="submit" class="btn btncolor secimetbtnadd">
                    Yadda saxla
                </button>
            </div>
        </div>
    </div>
</form>
