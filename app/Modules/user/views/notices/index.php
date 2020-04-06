<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use \Helpers\Pagination;
use Models\LanguagesModel;
$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">

    <?php include "_search.php"; ?>

    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to("user/".$params["name"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="datatable2" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Notice</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $item){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20"><?= $item["id"]?></td>
                                    <td class="admin-arrow-box"><?=($item['viewed']==0)?'<span style="color:red;font-weight: bold">New</span>':''?> <a href="view/<?= $item["id"]?>"><?=$item['notice_title']?></a></td>
                                    <td class="admin-arrow-box"><?=date('m/d/Y',$item['time'])?></td>
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
