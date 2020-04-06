<?php
use \Helpers\Csrf;
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
                    <div class="half_box_title">Add Charge</div>
                    <div class="half_box_body">
                        <form action="" method="post">
                            <div class="form-group">
                                <input class="form-control admininput" type="text" name="amount" placeholder="Amount"/>
                            </div>
                            <input type="hidden" value="<?= Csrf::makeToken();?>" name="csrf_token">
                            <button type="submit" class="btn btncolor ">Add</button>
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
                        <li><a href="">Add Charge</a></li>
                        <li><a href="">Add Credit</a></li>
                        <li><a href="">Add Receipt</a></li>
                        <li><a href="">View Online Portal</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</section><!-- /.content -->