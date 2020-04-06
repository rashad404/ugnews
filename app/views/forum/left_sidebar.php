<?php use Helpers\Format;?>

<div class="forum_sidebar">
    <div class="forum_sidebar_title">
        <i class="fa fa-caret-down"></i><?=$lng->get('Categories')?>
    </div>
    <div class="forum_sidebar_title_mob">
        <div style="float: left"><?=$lng->get('Categories')?></div>
        <i class="fa fa-times forum_sidebar_close visible-xs" style="float: right"></i>
        <div class="clearBoth"></div>
    </div>
    <ul class="forum_sidebar_content">
        <li><a <?=($data['selected_cat']==0)?'class="selected"':''?> href="forum"><?=$lng->get('All categories')?></a></li>
        <?php foreach ($data['category_list'] as $list): ?>
            <li>
                <a <?=($list['id']==$data['selected_cat'])?'class="selected"':''?> href="forum/cat/<?=Format::urlText($list['id'])?>/<?=$list['title_'.$data['def_language']]?>"><?=$list['title_'.$data['def_language']]?></a>
            </li>
        <?php endforeach;?>
    </ul>
</div>