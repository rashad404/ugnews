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
                                <th><?=$lng->get('Landlord')?></th>
                                <th><?=$lng->get('Start Date')?></th>
                                <th><?=$lng->get('End Date')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $item){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20"><?= $item["id"]?></td>
                                    <td class="admin-arrow-box">
                                        <a href="view/<?=$item['id']?>">
                                            <?= $item["partner_first_name"].' '.$item["partner_middle_name"].' '.$item["partner_last_name"]?>
                                        </a>
                                    </td>
                                    <td class="admin-arrow-box"><?=$item['start_date']?></td>
                                    <td class="admin-arrow-box"><?=$item['end_date']?></td>
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
