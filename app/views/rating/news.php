<?php

use Helpers\Format;
use Helpers\Url;

?>
<main class="main">
    <div class="container paddingBottom20 paddingTop40">
        <div class="row paddingBottom40">
            <div class="col-sm-12">

                <div class="rating_box_title"><?=$lng->get('TOP News')?></div>
                <div class="rating_box">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 10px;padding: 10px!important;"></th>
                            <th><?=$lng->get('News title')?></th>
                            <th><?=$lng->get('View')?></th>
                        </tr>
                        <?php $c=$data['startRow']+1; foreach ($data['list'] as $list):?>
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
                            ?>
                            <tr>
                                <td class="rating_item_count"><span style="background-color: <?=$color?>"><?=$c?></span></td>

                                <td class="rating_item_title"><a target="_blank" href="<?=$list['slug']?>"><?=Format::listTitle($list['title'],60)?></a> </td>
                                <td class="rating_item"><?=$list['view']?></td>
                            </tr>
                        <?php $c++;endforeach; ?>
                    </table>

                    <div class="clearBoth"></div>
                    <div style="text-align:center;">
                        <?php echo $data["pagination"]->pageNavigation('pagination')?>
                    </div>
                </div>


            </div>
        </div>
    </div>
</main>