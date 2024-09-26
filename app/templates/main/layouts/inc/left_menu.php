<?php if($userId>0):?>
    <ul class="nav space-y-2 mb-4">
        <li class="font-semibold text-lg text-maroon-800"><?=$lng->get('Author Portal')?></li>
        <li><a href="/partner/news/index" class="block py-1 text-maroon-700 hover:text-maroon-800"><?=$lng->get('Your News')?></a></li>
        <li><a href="/partner/channels/index" class="block py-1 text-maroon-700 hover:text-maroon-800"><?=$lng->get('Your Channels')?></a></li>
        <li><a href="/partner/settings/defaults" class="block py-1 text-maroon-700 hover:text-maroon-800"><?=$lng->get('Channel Settings')?></a></li>
        <li class="font-semibold text-lg text-maroon-800 mt-4"><?=$lng->get('Ad Portal')?></li>
        <li><a href="/partner/ads/add" class="block py-1 text-maroon-700 hover:text-maroon-800"><?=$lng->get('Create ads')?></a></li>
        <li><a href="/partner/ads/index" class="block py-1 text-maroon-700 hover:text-maroon-800"><?=$lng->get('Your ads')?></a></li>
        <li class="font-semibold text-lg text-maroon-800 mt-4"><?=$lng->get('Categories')?></li>
    </ul>
<?php endif;?>
<?= $menuListMobile;?>