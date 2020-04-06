<?php
use Helpers\Url;
use Models\api\ExpressModel;

$params = $data["dataParams"];
$lang = $params['lang'];

?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="headtext">
        <span><a href="../index"><span style="color:#8bc34a;"><?= $params["cTitle"]; ?></span></a> / Bax</span>
    </div>
</section>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="box">
                <div class="box-header">
                    <div class="col-xs-12">
                        <h3 class="box-title">Baxış</h3>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-xs-12 secimet">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <table class="table">
                                    <tbody>
                                    <?php if($data['result']['send']==0){?>
                                    <tr>
                                        <td><a class="newLinks" href="../send/<?=$data['result']['id']?>"><?=$lang->get('Send to Bank')?></a></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <th><?=$lang->get('Bank card')?></th>
                                        <td><?= $data['result']['card']?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Name')?></th>
                                        <td><?= $data['result']['name'].' '.$data['result']['surname'].' '.$data['result']['father_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Name on card')?></th>
                                        <td><?= $data['result']['card_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Phone')?></th>
                                        <td><?= $data['result']['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('E-mail')?></th>
                                        <td><?= $data['result']['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Birth Date')?></th>
                                        <td><?= $data['result']['birth_day'].".".$data['result']['birth_month'].".".$data['result']['birth_year'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Passport')?></th>
                                        <td><?= $data['result']['passport'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Pin')?></th>
                                        <td><?= $data['result']['pin'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Branch')?></th>
                                        <?php $branches = ExpressModel::getBranches();?>
                                        <td><?= $branches[$data['result']['branch']][1] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Registration address')?></th>
                                        <td><?= $data['result']['reg_address'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Current address')?></th>
                                        <td><?= $data['result']['current_address'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Work phone')?></th>
                                        <td><?= $data['result']['work_phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Home phone')?></th>
                                        <td><?= $data['result']['home_phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Status')?></th>
                                        <td><?= ($data['result']['status']==0 ? $lang->get('Deactive') : $lang->get('Active')) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Date')?></th>
                                        <td><?= date("d-m-Y H:i:s", $data['result']['time']) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <table class="table">
                                <tr>
                                    <th><?=$lang->get('Passport front photo')?></th>
                                    <td>
                                        <a target="_blank" href="<?= SITE_URL.Url::uploadPath().'passport_front/'.$data['result']['passport_photo_front'] ?>.jpg?rand=<?=rand(111111111,999999999)?>">
                                            <img style="width:250px;height:175px;" src="<?= SITE_URL.Url::uploadPath().'passport_front/'.$data['result']['passport_photo_front'] ?>.jpg?rand=<?=rand(111111111,999999999)?>" alt="passport front photo"/>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=$lang->get('Passport back photo')?></th>
                                    <td>
                                        <a target="_blank" href="<?= SITE_URL.Url::uploadPath().'passport_back/'.$data['result']['passport_photo_back'] ?>.jpg?rand=<?=rand(111111111,999999999)?>">
                                            <img style="width:250px;height:175px;" src="<?= SITE_URL.Url::uploadPath().'passport_back/'.$data['result']['passport_photo_back'] ?>.jpg?rand=<?=rand(111111111,999999999)?>" alt="passport back photo"/>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <?php
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

