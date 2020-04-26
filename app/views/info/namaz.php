<?php

use Helpers\Format;
use Helpers\Url;

?>
<main class="main">
    <div class="container paddingBottom20 paddingTop40">
        <div class="row paddingBottom40">
            <div class="col-sm-12">

                <div class="rating_box_title"><?=$lng->get('Baku')?> <?=$lng->get('Namaz Times')?></div>

                <div class="rating_box info_box_table table-responsive">
                    <table class="table table-striped"  >
                        <tr>
                            <th><?=$lng->get('Date')?></th>
                            <th><?=$lng->get('Fajr')?></th>
                            <th><?=$lng->get('Sunrise')?></th>
                            <th><?=$lng->get('Dhuhr')?></th>
                            <th><?=$lng->get('Asr')?></th>
                            <th><?=$lng->get('Maghrib')?></th>
                            <th><?=$lng->get('Isha')?></th>
                        </tr>

                        <tr style="color:red;font-weight: bold">
                            <td class="rating_item_title"><?=$lng->get('Today')?></td>
                            <td class="rating_item"><?=$data['today']['fajr']?></td>
                            <td class="rating_item"><?=$data['today']['sunrise']?></td>
                            <td class="rating_item"><?=$data['today']['dhuhr']?></td>
                            <td class="rating_item"><?=$data['today']['asr']?></td>
                            <td class="rating_item"><?=$data['today']['maghrib']?></td>
                            <td class="rating_item"><?=$data['today']['isha']?></td>
                        </tr>
                        <?php foreach ($data['list'] as $list):?>
                            <?php
                                if($list['date']==date('Y-m-d')){
                                    $style = 'color:red';
                                }else{
                                    $style = '';
                                }
                            ?>
                            <tr style="<?=$style?>">
                                <td class="rating_item_title"><?=$list['date']?></td>
                                <td class="rating_item"><?=$list['fajr']?></td>
                                <td class="rating_item"><?=$list['sunrise']?></td>
                                <td class="rating_item"><?=$list['dhuhr']?></td>
                                <td class="rating_item"><?=$list['asr']?></td>
                                <td class="rating_item"><?=$list['maghrib']?></td>
                                <td class="rating_item"><?=$list['isha']?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                    <div class="clearBoth"></div>
                </div>


            </div>
        </div>
    </div>
</main>