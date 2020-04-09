<?php

$item = $data['item'];

$lng = $data['lng'];
?>


<section class="content">
    <form action="" method="post">
        <div class="row">

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Default settings')?>:</div>
                    <div class="half_box_body">
                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('My first name')?></td>
                                <td>
                                    <select name="first_name_share">
                                        <option value="0" <?=($item['first_name_share']==0)?'selected':''?>><?=$lng->get('Don\'t share')?></option>
                                        <option value="1" <?=($item['first_name_share']==1)?'selected':''?>><?=$lng->get('Housemates only')?></option>
                                        <option value="2" <?=($item['first_name_share']==2)?'selected':''?>><?=$lng->get('All Users')?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('My photo')?></td>
                                <td>
                                    <select name="photo_share">
                                        <option value="0" <?=($item['photo_share']==0)?'selected':''?>><?=$lng->get('Don\'t share')?></option>
                                        <option value="1" <?=($item['photo_share']==1)?'selected':''?>><?=$lng->get('Housemates only')?></option>
                                        <option value="2" <?=($item['photo_share']==2)?'selected':''?>><?=$lng->get('All Users')?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('My age')?></td>
                                <td>
                                    <select name="age_share">
                                        <option value="0" <?=($item['age_share']==0)?'selected':''?>><?=$lng->get('Don\'t share')?></option>
                                        <option value="1" <?=($item['age_share']==1)?'selected':''?>><?=$lng->get('Housemates only')?></option>
                                        <option value="2" <?=($item['age_share']==2)?'selected':''?>><?=$lng->get('All Users')?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('My Tenant score')?></td>
                                <td>
                                    <select name="score_share">
                                        <option value="0" <?=($item['score_share']==0)?'selected':''?>><?=$lng->get('Don\'t share')?></option>
                                        <option value="1" <?=($item['score_share']==1)?'selected':''?>><?=$lng->get('Housemates only')?></option>
                                        <option value="2" <?=($item['score_share']==2)?'selected':''?>><?=$lng->get('All Users')?></option>
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
