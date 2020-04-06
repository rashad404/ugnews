<?php
$params = $data["dataParams"];
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= $params["cTitle"]?></h1>
        <div class="panel panel-green">
            <div class="panel-heading">
                Əlaqə məlumatlarını dəyiş
            </div>
            <?php
            include("_form.php");
            ?>
        </div>
    </div>
</div>