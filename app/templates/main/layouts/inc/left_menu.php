<?php if($userId>0):?>
    <ul class="nav">
        
            <li class="menu_sub_total"><?=$lng->get('Author Portal')?></li>
            <li><a href="/partner/news/index"><?=$lng->get('Your News')?></a></li>
            <li><a href="/partner/channels/index"><?=$lng->get('Your Channels')?></a></li>
            <li><a href="/partner/settings/defaults"><?=$lng->get('Channel Settings')?></a></li>
            <li class="menu_sub_total"><?=$lng->get('Ad Portal')?></li>
            <li><a href="/partner/ads/add"><?=$lng->get('Create ads')?></a></li>
            <li><a href="/partner/ads/index"><?=$lng->get('Your ads')?></a></li>

            <li class="menu_sub_total"><?=$lng->get('Categories')?></li>
        
    </ul>
<?php endif;?>
<?= $menuListMobile;?>