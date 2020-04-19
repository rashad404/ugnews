<?php
use Models\LanguagesModel;
$params = $data["params"];
$lng = $data["lng"];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="../index"><span style="color:#8bc34a;"><?= $params["title"]; ?></span></a> / <?=$lng->get('Edit')?></span>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <?php
                include("_form.php");
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->