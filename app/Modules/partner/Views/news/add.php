<?php
use Models\LanguagesModel;
$params = $data["params"];
$languages = LanguagesModel::getLanguages('partner');
$defaultLanguage = LanguagesModel::getDefaultLanguage('partner');
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="index"><span style="color:var(--main-color-hover-on-white);"><?= $params["title"]; ?></span></a> / <?=$lng->get('Add');?></span>
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