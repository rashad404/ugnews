<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use \Helpers\Pagination;
use Models\LanguagesModel;
$params = $data['params'];
$item = $data['item'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="headtext">
        <a href="../index"><?=$params['title']?></a> / <span style="font-weight: bold"><?= $item["notice_title"];?></span>
    </div>
</section>

<section class="content">
    <div class="row half_box half_box_orange">
        <div class="col-sm-8">

            <div style="text-align: center;font-weight: bold; font-size: 18px"><?= $item["notice_title"];?></div>
            <div style="text-align: center; font-size: 16px">Date: <?= date('m/d/Y', $item["time"]);?></div>

            <div><?=html_entity_decode($item['notice_text'])?></div>
        </div>


</section><!-- /.content -->
