<?php

$item = $data['item'];
$lng = $data['lng'];
?>


<section class="content">
    <form action="" method="post">
        <div class="row">

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Settings for new posts')?></div>
                    <div class="half_box_body">
                        <table class="default_vertical default">
                            <tr>
                                <td><?=$lng->get('Default Country')?>:</td>
                                <td>
                                    <select name="country">
                                        <?php foreach (\Models\CountryModel::getList() as $list):?>
                                            <option value="<?=$list['id']?>" <?=($list['id']==$item['country'])?'selected':''?>><?=$list['name']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Default Language')?>:</td>
                                <td>
                                    <select name="language">
                                        <?php foreach (\Models\LanguagesModel::getLanguages() as $list):?>
                                            <option value="<?=$list['id']?>" <?=($list['id']==$item['language'])?'selected':''?>><?=$list['name']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                                    <button type="submit" class="btn btn-success">
                                        <?=$lng->get('Update settings')?>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>
</section><!-- /.content -->
