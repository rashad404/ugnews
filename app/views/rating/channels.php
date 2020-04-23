<?php

use Helpers\Url;

?>
<main class="main">
    <div class="container paddingBottom20 paddingTop40">
        <div class="row paddingBottom40">
            <div class="col-sm-12">

                <div class="rating_box_title"><?=$lng->get('TOP Channels')?></div>

                <div class="rating_box table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 10px;padding: 10px!important;"></th>
                            <th style="width: 5px;"></th>
                            <th><?=$lng->get('Channel')?></th>
                            <th><?=$lng->get('Subscribers')?></th>
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
                                <td class="rating_item_img">
                                    <?php if (!empty($list['thumb'])): ?>
                                        <img src="<?=Url::filePath()?>/<?=$list['thumb']?>" alt="" />
                                    <?php endif;?>
                                </td>
                                <td class="rating_item"><a href=""><?=$list['name']?></a> </td>
                                <td class="rating_item"><?=$list['subscribers']?></td>
                            </tr>
                        <?php $c++;endforeach; ?>
                    </table>
                </div>


                <div class="clearBoth"></div>
                <div style="text-align:center;">
                    <?php echo $data["pagination"]->pageNavigation('pagination')?>
                </div>

            </div>
        </div>
    </div>
</main>