<?php
use Models\LanguagesModel;
use Modules\partner\Models\RoomsModel;
$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();

//\Helpers\Console::varDump($data['item']['name']);

$price_1 = $data['item']['rent']+$data['item']['utility']+$data['item']['profit'];
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xs-6 total_stats">
                <div><span class="table_key"><?=$lng->get('Apartment Name')?>:</span> <span class="table_value"><?=$data['item']['name']?></span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Rent')?>:</span> <span class="table_value">$<?=$data['item']['rent']?> $</span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Utility')?>:</span> <span class="table_value"><?=$data['item']['utility']?> $</span></div><div class="clearBoth"></div>
                <div><span class="table_key"><?=$lng->get('Profit')?>:</span> <span class="table_value"><?=$data['item']['profit']?> $</span></div><div class="clearBoth"></div>
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <table class="prices_table">
                    <th><?=$lng->get('Apt Model')?></th>
                    <?php foreach ($data['lease_terms'] as $lease_terms):?>
                        <th><?=$lease_terms['name']?></th>
                    <?php endforeach;?>
                    <?php foreach ($data['apt_models'] as $model):?>
                        <tr>
                            <td><?=$model['name']?></td>
                            <?php foreach ($data['lease_terms'] as $lease_terms):?>
                                <td>
                                    <?php
                                    $room_array = explode('+',$model['name']);
                                    $total_beds = 0;
                                    foreach($room_array as $room_beds) {
                                        $total_beds += $room_beds;
                                    }
                                    foreach($room_array as $room_beds){
                                        $apt_price = $price_1 + $price_1*$lease_terms['increase']/100;
                                        if($total_beds==1){
                                            $bed_price = $apt_price;
                                        }
                                        if($total_beds==2){
                                            $bed_price = $apt_price/2;
                                        }
                                        if($total_beds==3 && $room_beds==1){
                                            $bed_price = $apt_price*0.4;
                                        }
                                        if($total_beds==3 && $room_beds==2){
                                            $bed_price = $apt_price*0.6/2;
                                        }
                                        if($total_beds==4){
                                            $bed_price = $apt_price/4;
                                        }

                                        if($total_beds==5 && $room_beds==1){
                                            $bed_price = $apt_price*0.35;
                                        }
                                        if($total_beds==5 && $room_beds==4){
                                            $bed_price = $apt_price*0.65/4;
                                        }

                                        if($total_beds==6 && $room_beds==2){
                                            $bed_price = $apt_price*0.4/2;
                                        }
                                        if($total_beds==6 && $room_beds==4){
                                            $bed_price = $apt_price*0.6/4;
                                        }

                                        echo '<div class="price_text">
                                              <span class="price_text_left">'.RoomsModel::getNameByBeds($room_beds).':</span> 
                                              <span class="price_text_right">'.number_format($bed_price,2).' $</span><div>';
                                        echo '<div style="clear: both;"></div>';
                                    }

                                    ?>
                                </td>
                            <?php endforeach;?>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
</section><!-- /.content -->