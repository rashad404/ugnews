<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use Models\LanguagesModel;
use Modules\partner\Models\TenantsModel;
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
            <form action="<?php echo Url::to(MODULE_PARTNER."/".$params["name"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="datatable2" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="width-20">#</th>
                                <th>Tenant</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <?php if($params["actions"]){ ?><th>Actions</th><?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $item){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20">
                                        <a class="btn btn-xs btn-success" target="_blank" href="<?=Url::to('partner/leases/view/'.$item["user_id"])?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                    <td class="admin-arrow-box">
                                        <a target="_blank" href="<?=Url::to('partner/tenants/view/'.$item["user_id"])?>"><?= $item["user_first_name"].' '.$item["user_middle_name"].' '.$item["user_last_name"]?></a>
                                    </td>
                                    <td class="admin-arrow-box">
                                        <?=($item['user_sign']==1)? '<span style="color:#008700;font-weight: bold">' .$lng->get('Signed').'</span>':$lng->get('Out for Sign')?>
                                    </td>
                                    <td class="admin-arrow-box"><?=$item['start_date']?></td>
                                    <td class="admin-arrow-box"><?=$item['end_date']?></td>

                                    <?php $opButtons = new OperationButtons();?>
                                    <?php if($params["actions"]){ ?>
                                        <td class="admin-arrow-box">
                                            <?= $opButtons->getCrudIconsDel($item["id"],MODULE_PARTNER."/".$params["name"])?>
                                        </td>
                                    <?php } ?>
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
