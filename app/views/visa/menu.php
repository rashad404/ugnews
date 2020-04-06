<div class="sidebar sidebar_popular">
    <div class="sidebar_title"><i class="fa fa-list-ul"></i><?=$lng->get('Visa types')?></div>
    <div class="sidebar_content">
        <ul>
            <li><a class="<?=($selected_menu=='index')?'selected':''?>" href="viza-desteyi"><?=$lng->get('General information')?></a> </li>
            <li><a class="<?=($selected_menu=='schengen')?'selected':''?>" href="shengen-vizasi"><?=$lng->get('Schengen visa')?></a> </li>
            <li><a class="<?=($selected_menu=='usa')?'selected':''?>" href="amerika-vizasi"><?=$lng->get('USA visa')?></a> </li>
            <li><a class="<?=($selected_menu=='uk')?'selected':''?>" href="ingiltere-vizasi"><?=$lng->get('United Kingdom visa')?></a> </li>
            <li><a class="<?=($selected_menu=='canada')?'selected':''?>" href="kanada-vizasi"><?=$lng->get('Canada visa')?></a> </li>
            <li><a class="<?=($selected_menu=='uae')?'selected':''?>" href="dubay-vizasi"><?=$lng->get('UAE visa')?></a> </li>
            <li><a class="<?=($selected_menu=='china')?'selected':''?>" href="chin-vizasi"><?=$lng->get('China visa')?></a> </li>
        </ul>
    </div>
</div>