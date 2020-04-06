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
        <a href="../index"><?=$params['title']?></a> / <span style="font-weight: bold"><?= $item["first_name"];?> <?= $item["last_name"];?></span><br/>
        Balance: <span style="color:red;font-weight: bold">$<?= $item["balance"];?></span><br/>
        Monthly Charges: <span style="color:red;font-weight: bold">$<?= $item["monthly_charges"];?></span>
    </div>
    <div>

    </div>
</section>

<section class="content">
    <div class="col-lg-10 col-md-12">

        <div class="row">

            <div class="col-sm-6">
                <div class="half_box_with_title">
                    <div class="half_box_title">Contact</div>
                    <div class="half_box_body">
                        <ul>
                            <li>Phone: <?= $item["phone"];?></li>
                            <li>E-mail: <?= $item["email"];?></li>
                            <li>Address: <?= $item["apt_address"]?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="half_box_with_title">
                    <div class="half_box_title">Tenant Status</div>
                    <div class="half_box_body">
                        <ul>
                            <li>Move In: <?= $item["move_in"];?></li>
                            <li>Move Out: <?= ($item["move_out"]=='')?'- -':$item["move_out"];?></li>
                            <li>Notice: <?= ($item["notice_date"]=='')?'- -':$item["notice_date"];?></li>
                        </ul>
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
                        <li><a href="../../balance/add_charge/<?= $item["id"];?>">Add Charge</a></li>
                        <li><a href="../../balance/add_credit/<?= $item["id"];?>">Add Credit</a></li>
                        <li><a href="../../balance/add_receipt/<?= $item["id"];?>">Add Receipt</a></li>
                        <li><a target="_blank" href="../view_portal/<?= $item["id"];?>">View Online Portal</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</section><!-- /.content -->