<?php
use \Helpers\Url;
?>
<div class="col-lg-4 col-md-12">
    <div class="row half_box half_box_orange">
        <div class="col-sm-12">
            <div>
                <ul class="right_block_lease">
                    <?php foreach($data['lease_pages'] as $page_array):?>
                        <?php if($page_array["id"]==$data['page_id']):?>
                            <li style="font-weight: bold">
                                <?= ($page_array["user_sign"]==1)?'<i class="fas fa-check"></i>':'&nbsp;&nbsp;&nbsp;&nbsp;'?>
                                <?= $page["title"]?>
                            </li>
                        <?php else:?>
                            <li>
                                <?= ($page_array["user_sign"]==1)?'<i class="fas fa-check"></i>':'&nbsp;&nbsp;&nbsp;&nbsp;'?>
                                <a href="/user/leases/view/<?=$item["id"]?>/<?=$page_array["id"]?>"><?= $page_array["title"]?></a>
                            </li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>
</div>