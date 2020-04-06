<?php
use Helpers\Url;
use Helpers\OperationButtons;
$params = $data["dataParams"];
$lng = $data['lng'];
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="../index"><span style="color:#8bc34a;"><?= $params["cTitle"]; ?></span></a> / Photos</span>
    </div>
</section>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <div class="box-header">
                    <div class="col-xs-12">
                        <h3 class="box-title"><?=$lng->get('Photos')?></h3>
                    </div>
                </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form_box">
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><strong><?=$lng->get('Select Photos')?>:</strong></label><br/>
                                        <input class="form-control admininput" type="file" placeholder="" name="file">
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                                    <button type="submit" name="submit" class="btn btn-success">
                                        <?=$lng->get('Upload')?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
            </div><!-- /.box -->
        </div>
    </div>

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to(MODULE_PARTNER."/".$params["cName"]."/album_operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <div class="dropdown secimet">
                            <a class="dropdown-toggle pointer secimetbtn" data-toggle="dropdown">
                                <span>Actions...<i class="fa fa-caret-down"></i></span>
                            </a>
                            <ul class="dropdown-menu top-40">
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.acbtnhid').click()">Activate</a>
                                    <input type="submit" class="hidden acbtnhid" name="active" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.deacbtnhid').click()">Deactivate</a>
                                    <input type="submit" class="hidden deacbtnhid" name="deactive" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.delbtnhid').click()">Delete</a>
                                    <input type="submit" class="hidden delbtnhid" name="delete" value="1">
                                </li>
                            </ul>
                        </div>
                        <table id="datatable2" class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th class="width-20">
                                    <div class="checkboxum">
                                        <label>
                                            <input type="checkbox" class="all-check">
                                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                        </label>
                                    </div>
                                </th>
                                <th class="width-20">#</th>
                                <th>Photo</th>
                                <?php if($params["cPositionEnable"]){ ?><th>Order</th><?php } ?>
                                <?php if($params["cStatusMode"]){ ?><th>Status</th><?php } ?>
                                <?php if($params["cCrudMode"]){ ?><th>Actions</th><?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $item){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20">
                                        <div class="checkboxum">
                                            <label>
                                                <input type="checkbox" name="row_check[]" value="<?= $item["id"]; ?>">
                                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="admin-arrow-box width-20"><?= $item["id"]?></td>
                                    <td class="admin-arrow-box">
                                        <img class="thumb_img" src="/<?= Url::uploadPath().'apt_album/'.$item["id"].'/thumb/'.$item["id"].'.jpg'?>" alt="<?= $item["id"]?>">
                                    </td>
                                    <?php $opButtons = new OperationButtons();?>
                                    <?php if($params["cPositionEnable"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getPositionIcons($item["id"],MODULE_PARTNER."/".$params["cName"])?></td>
                                    <?php } ?>
                                    <?php if($params["cStatusMode"]){ ?>
                                        <td class="admin-arrow-box"> <?= $opButtons->getStatusIcons($item["id"],$item["status"]); ?> </td>
                                    <?php } ?>
                                    <?php if($params["cCrudMode"]){ ?>
                                        <td class="admin-arrow-box">
                                            <a href="/<?=MODULE_PARTNER."/".$params["cName"]?>/delete_album_photo/<?=$item["id"]?>" title="" data-toggle="tooltip" class="btn btn-xs btn-danger delete_confirm" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->