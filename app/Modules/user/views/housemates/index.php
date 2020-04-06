<?php
use Models\LanguagesModel;
use Helpers\Date;
$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">

    <div class="row">
        <?php foreach ($data['list'] as $list):?>
        <div class="col-md-4">
            <div class="housemates">
                <?php if($list['id']!=267):?>
                <div class="housmates_name"><?=($list['id']==$data['user_id'])?'<span class="housemates_you">'.$lng->get('You').'</span>':$list['first_name']?></div>
                <div><?=$lng->get('Age')?>: <?=Date::dateToAge($list['birthday'])?></div>
                <?php endif;?>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</section><!-- /.content -->
