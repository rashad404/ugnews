<?php
use Modules\partner\Models\ChannelsModel;
$item = $data['item'];
$lng = $data['lng'];
?>

<section class="content-header">
    <div class="headtext">
        <span><?=$lng->get('Settings for new posts')?></span>
    </div>
</section>
<section class="content">
    <form action="" method="post">
        <div class="row">

            <div class="col-sm-12">
                <div class="half_box">
                    <div class="half_box_body">
                        <table class="default_vertical default">
                            <tr>
                                <td><?=$lng->get('Default Channel')?>:</td>
                                <td>
                                    <?php
                                    new ChannelsModel();
                                    $data = ChannelsModel::getList();
                                    ?>
                                    <select name="channel">
                                        <?php
                                            foreach ($data as $list):?>
                                            <option value="<?=$list['id']?>" <?=($list['id']==$item['channel'])?'selected':''?>><?=$list['name']?></option>
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
