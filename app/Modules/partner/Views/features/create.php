<?php
use Models\LanguagesModel;
$params = $data["dataParams"];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = $params['defaultLang'];
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="index"><?= $params["cTitle"]; ?></a> / Əlavə et</span>
    </div>
</section>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <div class="box-header">
                    <div class="col-xs-6">
                        <h3 class="box-title">Əlavə et</h3>
                    </div>
                </div>
                <?php
                include("_form.php");
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->