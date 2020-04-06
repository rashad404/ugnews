<?php
use \Helpers\Csrf;
use Models\LanguagesModel;
$params = $data['params'];
$item = $data['item'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="header_info">
        <a href="../../tenants/index"><?=$params['title']?></a> / <span style="font-weight: bold"><?= $item["first_name"];?> <?= $item["last_name"];?></span><br/>
        Balance: <span style="color:red;font-weight: bold">$<?= $item["balance"];?></span><br/>
        Monthly Charges: <span style="color:red;font-weight: bold">$<?= $item["rent"];?></span>
    </div>
    <div>

    </div>
</section>

<section class="content">
    <div class="col-lg-10 col-md-12">

        <div class="row">

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Add Credit')?></div>
                    <div class="half_box_body">
                        <form action="" method="post">
                            <div class="form-group">
                                <input class="form-control admininput" type="text" name="amount" placeholder="<?=$lng->get('Amount')?>"/>
                            </div>
                            <div class="form-group">
                                <input class="form-control admininput" type="text" name="description" placeholder="<?=$lng->get('Description')?>"/>
                            </div>
                            <input type="hidden" value="<?= Csrf::makeToken();?>" name="csrf_token">
                            <button type="submit" class="btn btncolor "><?=$lng->get('Add')?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="col-lg-2 col-md-12">

        <div class="row half_box half_box_orange">
            <div class="col-sm-12">
                <div>
                    <ul class="right_block">
                        <li><a href="../../balance/add_charge/<?= $item["id"];?>"><?=$lng->get('Add Charge')?></a></li>
                        <li><a href="../../balance/add_credit/<?= $item["id"];?>"><?=$lng->get('Add Credit')?></a></li>
                        <li><a href="../../balance/add_receipt/<?= $item["id"];?>"><?=$lng->get('Add Receipt')?></a></li>
                        <li><a target="_blank" href="../view_portal/<?= $item["id"];?>"><?=$lng->get('View Online Portal')?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</section><!-- /.content -->