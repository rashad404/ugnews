<?php

use Helpers\Format;
use Helpers\Url;

?>
<main class="main">
    <div class="container paddingBottom20 paddingTop40">
        <div class="row paddingBottom40">
            <div class="col-sm-12">

                <div class="rating_box_title"><?=$lng->get('Namaz Times')?></div>

                <div class="rating_box info_box_table table-responsive">
                    <table class="table table-striped"  >
                        <tr>
                            <th style="width: 10px;padding: 10px!important;"></th>
                            <th><?=$lng->get('Date')?></th>
                            <th><?=$lng->get('Fajr')?></th>
                            <th><?=$lng->get('Sunrise')?></th>
                            <th><?=$lng->get('Dhuhr')?></th>
                            <th><?=$lng->get('Asr')?></th>
                            <th><?=$lng->get('Maghrib')?></th>
                            <th><?=$lng->get('Isha')?></th>
                        </tr>

                        <?php $c=1; foreach ($data['list'] as $list):?>
                            <tr>
                                <td class="rating_item_count"><span style="background-color: <?=$color?>"><?=$c?></span></td>
                                <td class="rating_item_title"><?=$list['date']?></td>
                                <td class="rating_item"><?=$list['fajr']?></td>
                                <td class="rating_item"><?=$list['sunrise']?></td>
                                <td class="rating_item"><?=$list['dhuhr']?></td>
                                <td class="rating_item"><?=$list['asr']?></td>
                                <td class="rating_item"><?=$list['maghrib']?></td>
                                <td class="rating_item"><?=$list['isha']?></td>
                            </tr>
                        <?php $c++;endforeach; ?>
                    </table>

                    <div class="clearBoth"></div>
                </div>


            </div>
        </div>
    </div>
</main>