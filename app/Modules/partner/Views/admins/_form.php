<?php
use Models\LanguagesModel;
$model = $data["model"];
$languages = LanguagesModel::getLanguages();

?>
<div class="panel-body">
    <div class="row">
        <form action="" method="post">
            <div class="col-md-12">
                <div class="tab-content form-content">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login" value="<?=$model ? $model['login'] : ''?>">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?=$model ? $model['email'] : ''?>">
                    </div>
                    <div class="form-group">
                        <label for="name">Ad familya</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?=$model ? $model['name'] : ''?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Şifrə</label>
                        <input type="text" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="role">Level</label>
                        <select class="form-control" id="role" name="role">
                            <?php foreach($data["dataParams"]["level"] as $key => $value): ?>
                            <?php
                                if($key < \Helpers\Session::get('auth_session_role')) continue;
                                if($model && $key == $model["role"]) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                            ?>

                            <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status </label>
                        <input class="switch_checkbox" id="status" type="checkbox" name="status" data-on-text="Aktiv" data-off-text="Deaktiv" value="1" <?php if($model && $model["status"]==0) echo ""; else echo "checked";?>>
                    </div>
                    <hr />

                    <div class="form-group">
                        <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">

                        <input type="submit" name="submit" value="Yadda saxla" class="btn btn-success pull-right">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.row (nested) -->
</div>
<!-- /.panel-body -->
