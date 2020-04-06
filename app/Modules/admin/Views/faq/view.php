<?php
use Models\LanguagesModel;
use Modules\admin\Models\MenusModel;

$params = $data["dataParams"];
$lang = $params['lang'];
$menusModel = new MenusModel();

?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="../index"><span style="color:#8bc34a;"><?= $params["cTitle"]; ?></span></a> / Bax</span>
    </div>
</section>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <div class="box-header">
                    <div class="col-xs-12">
                        <h3 class="box-title">Baxış</h3>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-xs-12 secimet">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="width: 100%;float: left"><strong>ID : <?= $data['result']['id'] ?></strong></label>
                                <label style="width: 100%;float: left"><strong><?=$lang->get('Question')?> :</strong></label> <?= $data['result']['title_az'] ?><br/><br/>
                                <label style="width: 100%;float: left"><strong><?=$lang->get('Answer')?> :</strong></label> <?= html_entity_decode($data['result']['text_az']) ?>

                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <?php
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

