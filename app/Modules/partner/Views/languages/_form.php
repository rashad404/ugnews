<?php
use Models\LanguagesModel;
use \Helpers\Url;
$languages = LanguagesModel::getLanguages();
$model = $data["model"];
?>
<div class="panel-body">
    <div class="row">
        <form action="" method="post">
            <div class="col-md-12">
                <ul class="nav nav-pills hidden">
                    <?php
                    foreach($languages as $language){
                        $li_class = '';
                        if($language["default"]) $li_class = 'active';
                        ?>
                        <li class="<?= $li_class?>"><a aria-expanded="false" href="#lang-<?= $language["name"]?>" data-toggle="tab"><?= $language["fullname"]?></a></li>
                    <?php }  ?>
                </ul>
                <div class="tab-content form-content"">
                    <?php
                    foreach($languages as $language){
                        $li_class = '';
                        if($language["default"]) $li_class = 'active in';
                        ?>
                        <div class="tab-pane fade <?= $li_class?>" id="lang-<?= $language["name"]?>">

                        </div>
                    <?php }  ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Adı</label>
                            <input class="form-control" name="fullname" value="<?=$model?$model["fullname"]:''?>">
                         </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group pull-left">
                            <label>Ikon</label>
                            <select class="form-control" onchange="show_flag(this.value)" name="flag" id="flag">
                                <?php
                                foreach($data["flags"] as $flag)
                                {
                                    if($model && $flag==$model["flag"]) $selected='selected="selected"'; else $selected='';
                                    $flagName=explode(".",$flag); $flagName=$flagName[0];
                                    echo '<option value="'.\Helpers\Url::templateModulePath().'images/flags/'.$flag.'" '.$selected.'>'.$flagName.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                        if($model) $flag=$model["flag"]; else $flag=$data["flags"][0];
                        ?> <img class="pull-left flag-icon" id="flag_image" src="<?= Url::templateModulePath()?>images/flags/<?php echo $flag; ?>" alt="" width="32" style="margin-bottom:-10px" />
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group pull-left">
                            <label>&nbsp;</label>
                            <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                            <input type="submit" class="btn btn-success form-control" name="submit" value="Yadda saxla" />
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <!-- /.row (nested) -->
</div>
<!-- /.panel-body -->
