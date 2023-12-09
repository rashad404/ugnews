<?php

use \Helpers\Url;
use \Helpers\OperationButtons;
use Helpers\Format;
use Models\LanguagesModel;
$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage('partner');

?>


<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">


    <?php include "_search.php";?>

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to(MODULE_PARTNER."/".$params["name"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">

                        <div class="secimet">
                            <?php if($data['channel_count']==0):?>
                                <div class="create_notice">
                                    <?=$lng->get('Please first create a news channel');?><br/>
                                    <a href="/partner/channels/index"><?=$lng->get('Create your first Channel');?></a>
                                </div>
                            <?php exit;endif;?>
                            <a class="dropdown-toggle pointer secimetbtn" data-toggle="dropdown">
                                <span><?=$lng->get('Actions');?>...<i class="fa fa-caret-down"></i></span>
                            </a>
                            <a href="<?php echo Url::to(MODULE_PARTNER."/".$params["name"]."/add")?>" class="btn btncolor secimetbtnadd">
                                <?=$lng->get('Add');?>
                                <i class="fa fa-plus afa"></i>
                            </a>
                            <ul class="dropdown-menu top-40">
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.acbtnhid').click()"><?=$lng->get('Activate');?></a>
                                    <input type="submit" class="hidden acbtnhid" name="active" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.deacbtnhid').click()"><?=$lng->get('Deactivate');?></a>
                                    <input type="submit" class="hidden deacbtnhid" name="deactive" value="1">
                                </li>
                                <li class="user-header admininbtn">
                                    <a class="pointer" onclick="javascript:$('.delbtnhid').click()"><?=$lng->get('Delete');?></a>
                                    <input type="submit" class="hidden delbtnhid" name="delete" value="1">
                                </li>
                            </ul>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable2" class="table table-striped">
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
                                <th><?=$lng->get('Title');?></th>
                                <?php if($params["position"]){ ?><th><?=$lng->get('Order');?></th><?php } ?>
                                <?php if($params["status"]){ ?><th><?=$lng->get('Status');?></th><?php } ?>
                                <?php if($params["actions"]){ ?><th><?=$lng->get('Operations');?></th><?php } ?>
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
                                        <a target="_blank" href="<?=SITE_URL?>/news/<?= $item["id"]?>/<?=Format::urlText($item['title'])?>"><?= $item["title"]?></a><br/>
                                        <?php
                                        if(date("d",$item['publish_time'])==date('d')){
                                            $news_date = date("H:i",$item['publish_time']);
                                        }else{
                                            $news_date = date("d.m.Y H:i",$item['publish_time']);
                                        }
                                        ?>
                                        <span style="color:#aaaaaa;font-size: 13px;"><?=$news_date?></span>
                                    </td>
                                    <?php $opButtons = new OperationButtons();?>

                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        </div>
                        <div style="text-align:center;">
                            <?php echo $data["pagination"]->pageNavigation('pagination')?>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div><!-- /.col -->

    </div><!-- /.row -->
</section><!-- /.content -->

<script>
    function copyFunction() {
        const $temp = $("<input>");
        $("body").append($temp);
        const copyText = event.target.innerHTML;
        $temp.val(copyText).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied: '+copyText);
    }
</script>
