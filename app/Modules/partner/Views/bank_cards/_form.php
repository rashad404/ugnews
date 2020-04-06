<?php
$model = $data["model"];
$lang = $params['lang'];

$input_array = [
    'card' => 'Bank card',
    'phone' => 'Phone',
    'email' => 'E-mail',
    'name' => 'Name',
    'surname' => 'Surname',
    'father_name' => 'Father name',
    'card_name' => 'Name on card',
    'passport' => 'Passport',
    'birth_day' => 'Birth day',
    'birth_month' => 'Birth month',
    'birth_year' => 'Birth year',
    'pin' => 'Pin',
    'private_code' => 'Private code',
    'reg_address' => 'Reg address',
    'current_address' => 'Current address',
    'work_phone' => 'Work phone',
    'home_phone' => 'Home phone',
]
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="box-body">
        <div class="col-sm-12">
            <div class="form-group">
                <label><strong><?=$lang->get('ID')?></strong>: <?=$model["id"]?></label>
            </div>
        </div>
        <?php foreach ($input_array as $key=>$value):?>

            <div class="col-sm-4">
                <div class="form-group">
                    <label><strong><?=$lang->get($value)?></strong></label>
                    <input type="text" name="<?=$key?>" value="<?=$model[$key]?>" class="form-control admininput">
                </div>
            </div>
        <?php endforeach;?>
        <div class="col-sm-4">
            <div class="form-group">
                <label><strong><?=$lang->get('Branch')?></strong></label>
                <select class="form-control admininput" name="branch" required>
                    <?php foreach ($data['branches'] as $branch): if ($model['branch'] == $branch[0]) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    } ?>
                        <option <?= $selected ?>
                                value="<?= $branch[0] ?>"><?= $branch[2] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

    </div><!-- /.box-body -->
    <div class="box-footer">
        <div class="col-xs-12">
            <div class="input-group pull-left">
                <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                <button type="submit" name="submit" class="btn btncolor secimetbtnadd">
                    <?=$lang->get('Save')?>
                </button>
            </div>
            <div class="input-group pull-right">
                <div class="pos-rel-top-6 ">
                    <label class="padyan15">Aktiv</label>
                    <input class="admin-switch" data-on-text="" data-off-text="" id="status" type="checkbox" name="status" value="1" <?php if($model && $model["status"]==0) echo ""; else echo "checked";?>>
                </div>
            </div>
        </div>
    </div>
</form>
