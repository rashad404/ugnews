<?php
use \Helpers\Url;
use \Helpers\OperationButtons;
use \Helpers\Pagination;
use Models\LanguagesModel;
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


    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="half_box_with_title">
                        <div class="half_box_body table-responsive">
                            <table class="default">
                                <tr>
                                    <th><?=$lng->get('Date')?></th>
                                    <th><?=$lng->get('Amount')?> (<?=DEFAULT_CURRENCY_SHORT?>)</th>
                                    <th><?=$lng->get('Type')?></th>
                                    <th><?=$lng->get('Description')?></th>
                                </tr>
                                <?php foreach ($data['balance_logs'] as $data):?>
                                    <tr>
                                        <td><?=date('m/d/Y H:i',$data['time'])?></td>
                                        <td><?=$data['amount']?></td>
                                        <td><?=$lng->get($data['action'])?></td>
                                        <td><?=$data['description']?></td>
                                    </tr>
                                <?php endforeach;?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->
</section><!-- /.content -->
