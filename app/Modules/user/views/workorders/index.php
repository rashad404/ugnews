<?php

use Helpers\Format;
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
                        <div class="dropdown secimet">
                            <a href="<?php echo Url::to("user/".$params["name"]."/add")?>" class="btn btncolor secimetbtnadd">
                                Create
                                <i class="fa fa-plus afa"></i>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable2" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Requested</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date Completed</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $item){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20"><?= $item["id"]?></td>
                                    <td class="admin-arrow-box">
                                        <?php
                                            $category_id = $item['category'];
                                            $category_list = \Modules\user\Models\WorkordersModel::getCategories();
                                            $category_name = $category_list[$category_id]['name'];
                                        ?>
                                        <?= $category_name?>
                                    </td>
                                    <td class="admin-arrow-box">
                                        <?php
                                            $location_id = $item['location'];
                                            $location_list = \Modules\user\Models\WorkordersModel::getLocations();
                                            $location_name = $location_list[$location_id]['name'];
                                        ?>
                                        <?= $location_name?>
                                    </td>
                                    <td><?=$item['date']?></td>
                                    <td><?=Format::getText($item['text'], 50)?></td>
                                    <td>
                                        <?php $status_list = \Modules\user\Models\WorkordersModel::getStatus();?>
                                        <?=$status_list[$item['status']]['name']?>
                                    </td>
                                    <td><?=($item['date_completed']!='0000-00-00')?$item['date_completed']:''?></td>
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
