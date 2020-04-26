<?php

use Helpers\Format;
use Helpers\Url;

?>
<main class="main">
    <div class="container paddingBottom20 paddingTop40">
        <div class="row paddingBottom40">
            <div class="col-sm-12">

                <div class="rating_box_title"><?=$lng->get('Coronavirus Statistics')?></div>

                <div class="rating_box info_box_table table-responsive">
                    <table class="table table-striped"  >
                        <tr>
                            <th style="width: 10px;padding: 10px!important;"></th>
                            <th><?=$lng->get('Country')?></th>
                            <th><?=$lng->get('Total')?></th>
                            <th><?=$lng->get('New')?></th>
                            <th><?=$lng->get('New Death')?></th>
                            <th><?=$lng->get('Total Death')?></th>
                            <th><?=$lng->get('Death Rate')?></th>
                            <th><?=$lng->get('Total Recovered')?></th>
                            <th><?=$lng->get('Active')?></th>
                            <th><?=$lng->get('Critical')?></th>
                        </tr>
                        <?php $c=1; foreach ($data['list'] as $list):?>
                            <?php
                                if($c==1){
                                    $color = 'red';
                                }elseif($c==2){
                                    $color = 'rgb(235, 0, 0)';
                                }elseif($c==3){
                                    $color = 'rgb(186, 0, 0)';
                                }else{
                                    $color = '#730C2E';
                                }
                                if($list['total_deaths']>0) {
                                    $death_rate = ($list['total_deaths'] / $list['total_cases']) * 100;
                                    $death_rate = number_format($death_rate, 2).'%';
                                }else{
                                    $death_rate = 0;
                                }
                            ?>
                            <tr>
                                <td class="rating_item_count"><span style="background-color: <?=$color?>"><?=$c?></span></td>
                                <td class="rating_item_title"><?=$lng->get($list['country'])?></td>
                                <td class="rating_item"><?=number_format($list['total_cases'],'0','', ',')?></td>
                                <td class="rating_item"><?=number_format($list['new_cases'],'0','', ',')?></td>
                                <td class="rating_item"><?=number_format($list['new_deaths'],'0','', ',')?></td>
                                <td class="rating_item"><?=number_format($list['total_deaths'],'0','', ',')?></td>
                                <td class="rating_item"><?=$death_rate?></td>
                                <td class="rating_item"><?=number_format($list['total_recovered'],'0','', ',')?></td>
                                <td class="rating_item"><?=number_format($list['active_cases'],'0','', ',')?></td>
                                <td class="rating_item"><?=number_format($list['critical'],'0','', ',')?></td>
                            </tr>
                        <?php $c++;endforeach; ?>
                    </table>

                    <div class="clearBoth"></div>
                </div>


            </div>
        </div>
    </div>
</main>