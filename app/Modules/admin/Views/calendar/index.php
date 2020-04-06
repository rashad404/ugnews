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
            <form action="<?php echo Url::to(MODULE_ADMIN."/".$params["name"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <table id="datatable2" class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th class="width-20">
                                    <div class="checkboxum">
                                        <label>
                                            <input type="checkbox" class="all-check">
                                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                        </label>
                                    </div>
                                </th>
                                <th class="width-20">#</th>
                                <th>Name</th>
                                <th>Apartment</th>
                                <th>Move in Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["list"]  as $item){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20">
                                        <div class="checkboxum">
                                            <label>
                                                <input type="checkbox" name="row_check[]" value="<?= $item["id"]; ?>">
                                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="admin-arrow-box width-20"><?= $item["id"]?></td>
                                    <td class="admin-arrow-box">
                                        <?= $item["first_name"]?> <?= $item["last_name"]?><br/>
                                        <div class="list_alt_text">
                                            <i class="fa fa-phone"></i> <?= $item["phone"]?> <i class="fa fa-envelope"></i> <?= $item["email"]?>
                                        </div>
                                    </td>
                                    <?php
                                        $time = strtotime($item["move_in"]);

                                        $datetime = new DateTime('tomorrow');
                                        $tomorrow = $datetime->format('Y-m-d');

                                        if($item["move_in"] == date("Y-m-d")) {
                                            $move_in_new_format = '<span style="color:#ff0707;font-weight: bold;">Today</span>';
                                        }elseif($item["move_in"] == $tomorrow) {
                                            $move_in_new_format = '<span style="color:#147bda;font-weight: bold;">Tomorrow</span>';
                                        }else{
                                            $move_in_new_format = date('M j',$time);
                                        }
                                        $apartment_name = \Modules\admin\Models\ApartmentsModel::getName($item['apt_id']);
                                        $room_name = \Modules\admin\Models\RoomsModel::getName($item['room_id']);
                                        $bed_name = \Modules\admin\Models\BedsModel::getName($item['bed_id']);
                                        $apt_name = $apartment_name.', '.$room_name.' '.$bed_name;
                                    ?>
                                    <td class="admin-arrow-box"><?= $apt_name?></td>
                                    <td class="admin-arrow-box"><?= $move_in_new_format?></td>


                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->