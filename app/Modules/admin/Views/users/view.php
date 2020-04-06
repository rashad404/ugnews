<?php
use Models\LanguagesModel;
use Modules\admin\Models\MenusModel;
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
                                    <tr>
                                        <th><?=$lang->get('ID')?></th>
                                        <td><?= $data['result']['id']?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Name')?></th>
                                        <td><?= $data['result']['name'].' '.$data['result']['surname'].' '.$data['result']['father_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Phone')?></th>
                                        <td><?= $data['result']['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('E-mail')?></th>
                                        <td><?= $data['result']['email'] ?></td>
                                    </tr>
                                        <th><?=$lang->get('Balance')?></th>
                                        <td><?= $data['result']['balance'].' '.DEFAULT_CURRENCY ?></td>
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
                                        <th><?=$lang->get('Withdraw limit')?></th>
                                        <td><?= $data['result']['withdraw_limit'].' '.DEFAULT_CURRENCY ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Registration time')?></th>
                                        <td><?= date("d-m-Y H:i",$data['result']['reg_time']) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?=$lang->get('Status')?></th>
                                        <td><?= ($data['result']['status']==0 ? $lang->get('Deactive') : $lang->get('Active')) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <?php
                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

