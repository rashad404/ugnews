<?php
$params = $data["params"];
$lng = $data['lng'];
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="index"><span style="color:var(--main-color);"><?= $params["title"]; ?></span></a> / <?=$lng->get('Add');?></span>
    </div>
</section>

<section class="content">
    <div class="row">

        <div class="col-12"><!-- /.box -->


            <div class="box">
                <?php
                include("_form.php");
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->