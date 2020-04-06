<?php
use Models\LanguagesModel;

$model = $data["model"];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();

?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-xs-12 secimet tab-content">

	        <?php foreach($languages as $k => $language){?>
                <div class="tab-pane fade <?= $k=='0' ? 'active in' : ''?>" id="lang-<?= $language["name"]?>">

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><strong>Ünvan</strong></label>
                            <input name="address_<?= $language["name"]?>" id="address_<?= $language["name"]?>" placeholder="" value="<?=$model?$model["address_".$language["name"]]:''?>"  class="form-control admininput">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><strong>Iş saatları</strong></label>
                            <input name="working_days_<?= $language["name"]?>" id="working_days_<?= $language["name"]?>" placeholder="" value="<?=$model?$model["working_days_".$language["name"]]:''?>"  class="form-control admininput">
                        </div>
                    </div>

                </div>
	        <?php } ?>


            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Iş nömrəsi</strong></label>
                    <input name="home_tel" id="mobile_tel" placeholder="" value="<?=$model?$model["home_tel"]:''?>"  class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Mobil telefon nömrəsi</strong></label>
                    <input name="mobile_tel" id="mobile_tel" placeholder="" value="<?=$model?$model["mobile_tel"]:''?>"  class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>E-mail</strong></label>
                    <input name="email" id="email" placeholder="" value="<?=$model?$model["email"]:''?>"  class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Facebook səhifəsi</strong></label>
                    <input name="facebook" id="facebook" placeholder="" value="<?=$model?$model["facebook"]:''?>"  class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Instagram səhifəsi</strong></label>
                    <input name="instagram" id="instagram" placeholder="" value="<?=$model?$model["instagram"]:''?>"  class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Twitter səhifəsi</strong></label>
                    <input name="twitter" id="twitter" placeholder="" value="<?=$model?$model["twitter"]:''?>"  class="form-control admininput">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><strong>Youtube səhifəsi</strong></label>
                    <input name="youtube" id="youtube" placeholder="" value="<?=$model?$model["youtube"]:''?>"  class="form-control admininput">
                </div>
            </div>
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
