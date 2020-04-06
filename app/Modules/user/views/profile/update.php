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
        <span><a href="../"><span style="color:#8bc34a;">Main</span></a> / <?=$lng->get('Update')?></span>
    </div>
</section>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <div class="box-header">
                    <div class="col-xs-6">
                        <h3 class="box-title"><?=$lng->get('Update')?></h3>
                    </div>
                    <div class="col-xs-6">
                        <ul class="nav nav-pills pull-right">
                            <?php
                            foreach($languages as $language){
                                $li_class = '';
                                if($language["default"]) $li_class = 'active';
                                ?>
                                <li class="<?= $li_class?>"><a aria-expanded="false" href="#lang-<?= $language["name"]?>" data-toggle="tab"><?= $language["fullname"]?></a></li>
                            <?php }  ?>
                        </ul>
                    </div>
                </div>
                <?php
                include("_form.php");
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->