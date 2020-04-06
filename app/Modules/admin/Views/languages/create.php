<?php
use \Helpers\Breadcrumbs;
use \Helpers\Url;
$params = $data["dataParams"];
?>
<div class="row">
    <div class="col-lg-12">

        <h4 class="page-header">
            <?= $params["cTitle"]?>
            <?php
                $bArray = [Url::to(MODULE_ADMIN.'/'.$params["cName"]) => $params["cTitle"],"Əlavə etme"];
                echo Breadcrumbs::getBreadCrumbs($bArray);
            ?>
        </h4>

        <div class="clearfix"></div>
        <div class="panel panel-green">
            <div class="panel-heading">
                Əlavə et
            </div>
            <?php
            include("_form.php");
            ?>
        </div>
    </div>
</div>