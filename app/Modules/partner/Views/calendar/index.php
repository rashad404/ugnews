<?php

use Helpers\Format;
use \Helpers\Url;
use Models\LanguagesModel;
use Modules\partner\Models\CalendarModel;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\CustomersModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\ShowingsModel;
use Modules\partner\Models\TenantsModel;

$params = $data['params'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();

$arrayMoveIn = CalendarModel::getListMoveIn();
$arrayMoveOut = CalendarModel::getListMoveOut();
$arrayShowings = CalendarModel::getListShowings();

    function date_compare($a, $b)
    {
        $t1 = strtotime($a['date']);
        $t2 = strtotime($b['date']);
        return $t1 - $t2;
    }
$array = array_merge($arrayMoveIn, $arrayMoveOut, $arrayShowings);


usort($array, 'date_compare');

?>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">


    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="<?php echo Url::to(MODULE_PARTNER."/".$params["name"]."/operation")?>" method="post">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <?php if(!empty($array)):?>
                            <table class="default">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Apartment</th>
                                </tr>
                            <?php foreach($array  as $item){ ?>
                                <?php

                                if(isset($item['guest_id'])){
                                    new CustomersModel();
                                    if($item['guest_id']>0){
                                        $tenant_info = CustomersModel::getItem($item['guest_id']);
                                        $item['first_name'] = $tenant_info['first_name'];
                                        $item['last_name'] = $tenant_info['last_name'];
                                        $item['phone'] = $tenant_info['phone'];
                                        $tenant_link = '<a href="../customers/view/'.$item["guest_id"].'">'.$item["first_name"].' '.$item["last_name"].'</a>';

                                    }elseif($item['user_id']>0){
                                        $tenant_info = TenantsModel::getItem($item['user_id']);
                                        $item['first_name'] = $tenant_info['first_name'];
                                        $item['last_name'] = $tenant_info['last_name'];
                                        $item['phone'] = $tenant_info['phone'];
                                        $tenant_link = '<a href="../tenants/view/'.$item["user_id"].'">'.$item["first_name"].' '.$item["last_name"].'</a>';
                                    }
                                    $type = ShowingsModel::getTypes($item['type']);
                                    $view = '<a class="btn btn-xs btn-info" href="../showings/update/'.$item['id'].'"><i class="fas fa-street-view"></i></a>';

                                }else{
                                    if(isset($item['move_in'])){
                                        $type = 'Move in';
                                        $view = '<a class="btn btn-xs btn-success" href="../tenants/view/'.$item["id"].'"><i class="fas fa-sign-in-alt"></i></a>';
                                    }else{
                                        $type = 'Move out';
                                        $view = '<a class="btn btn-xs btn-danger" href="../tenants/view/'.$item["id"].'"><i class="fas fa-sign-out-alt"></i></a>';
                                    }
                                    $tenant_link = '<a href="../tenants/view/'.$item["id"].'">'.$item["first_name"].' '.$item["last_name"].'</a>';
                                }
                                ?>
                                <tr>
                                    <td style="width: 50px;">
                                        <?=$view?>
                                    </td>
                                    <td>
                                        <?= $tenant_link?><br/>
                                        <div class="list_alt_text">
                                            <i class="fa fa-phone"></i> <span style="color:#496086;cursor:pointer;" onclick="copyFunction()"><?=Format::phoneNumber($item['phone'])?></span>
                                        </div>
                                    </td>
                                    <?php
                                        $time = strtotime($item["date"]);

                                        $datetime = new DateTime('tomorrow');
                                        $tomorrow = $datetime->format('Y-m-d');

                                        if($item["date"] == date("Y-m-d")) {
                                            $date_new_format = '<span style="color:#ff0707;font-weight: bold;">Today</span>';
                                        }elseif(date("Y-m-d",$time) == date("Y-m-d")) {
                                            $date_new_format = '<span style="color:#ff0707;font-weight: bold;">'.date('h:i A', $time).'</span>';
                                        }elseif(date("Y-m-d", $time) == $tomorrow) {
                                            $date_new_format = '<span style="color:#147bda;font-weight: bold;">Tomorrow</span>';
                                        }else{
                                            $date_new_format = date('M j',$time);
                                        }
                                        $apartment_name = ApartmentsModel::getName($item['apt_id']);
                                        $room_name = RoomsModel::getName($item['room_id']);
                                        $bed_name = BedsModel::getName($item['bed_id']);
                                        $apt_name = $apartment_name.', '.$room_name.' '.$bed_name;

                                    ?>
                                    <td><?= $date_new_format?></td>
                                    <td><?= $type?></td>
                                    <td><?= $apt_name?></td>


                                </tr>
                            <?php } ?>
                        </table>
                            <?php else:?>
                                <div class="no_data"><?=$lng->get('You don\'t have any event.')?></div>
                            <?php endif;?>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </form>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->


<script>
    function copyFunction() {
        const $temp = $("<input>");
        $("body").append($temp);
        const copyText = event.target.innerHTML;
        $temp.val(copyText).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied: '+copyText);
    }
</script>
