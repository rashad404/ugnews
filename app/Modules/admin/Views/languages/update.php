<?php
use \Helpers\Breadcrumbs;
use \Helpers\Url;
$params = $data["dataParams"];
$model = $data["model"];
?>
<div class="row">
    <div class="col-lg-12">

        <h4 class="page-header"><?= $params["cTitle"]?>
            <?php
                $bArray = [Url::to(MODULE_ADMIN.'/'.$params["cName"]) => $params["cTitle"],"Düzəliş et: #".$model["id"]];
                echo Breadcrumbs::getBreadCrumbs($bArray);
            ?>
        </h4>

        <div class="panel panel-green">
            <div class="panel-heading">
                Düzəliş et
            </div>
            <?php
            include("_form.php");
            ?>
        </div>
    </div>
</div>