<?php
use Helpers\Csrf;
use Helpers\Date;
use Helpers\Url;
use Helpers\Features;
use Helpers\Format;
use Models\ApartmentsModel;

?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row inner_background">
                <div class="col-sm-12">
                    <h1 class="page_title"><?=$lng->get('Schedule Showing')?></h1>
                </div>
            </div>
        </div>
        <div class="container default">
            <div class="row paddingBottom40">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog_inner_text custom_block">

                                <div class="breadcrumsbs_in">
                                    <a href="apartments"><?=$lng->get("Apartments")?></a> /
                                    <?=$lng->get('Showing')?>
                                </div>

                                <form action="" method="POST">
                                    <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                                    <input type="hidden" value="<?=$postData['apt_id']?>" name="apt_id" />
                                    <input type="hidden" value="<?=$postData['room_id']?>" name="room_id" />
                                    <input type="hidden" value="<?=$postData['bed_id']?>" name="bed_id" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label><?=$lng->get("When are you available?")?></label> <span class="required_star">*</span><br/>
                                            <div class="form-group default_date">
                                                <div class="input-group date" id="datetimepicker1">
                                                    <input type="text" name="date" value="<?=$postData['date']?>">
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label><?=$lng->get("Desired move-in Date")?></label> <span class="required_star">*</span><br/>
                                            <div class="form-group default_date">
                                                <div class="input-group date" id="datetimepicker2">
                                                    <input type="text" name="movein_date" value="<?=$postData['movein_date']?>">
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <label><?=$lng->get('Do you have pets?')?> <span class="required_star">*</span>:</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="same_line_select" name="animals" required>
                                                <option <?=($postData['animals'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                                <option <?=($postData['animals'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <label><?=$lng->get('Notes')?>:</label>
                                            <textarea rows="5" name="note" placeholder="Notes (not necessary)"><?=$postData['note']?></textarea>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Submit')?></button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom_block">
                        <h3 class="title"><?=$lng->get('Your info')?> <a href="user_panel/profile" target="_blank"><i class="fas fa-edit"></i></a> </h3>
                        <hr class="dark_purple"/>
                        <table>
                            <tr>
                                <td>
                                    <div class="profile_photo">
                                        <?php if (file_exists(Url::uploadPath() . 'users/' . $data['userId'] . '.jpg')): ?>
                                            <img src="<?= Url::uploadPath() . 'users/' . $data['userId'] ?>.jpg?ref=<?= rand(1111111, 9999999) ?>" title="">
                                        <?php else: ?>
                                            <img src="<?= URL::templatePath() ?>/img/profile_photo-02.png" title="">
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="padding-left: 10px;">
                                        <div><label><?=$lng->get('Name')?>:</label> <?=$postData['first_name']?> <?=$postData['last_name']?></div>
                                        <div><label><?=$lng->get('Age')?>:</label> <?=Date::yearToAge($postData['birth_year'])?></div>
                                        <div><label><?=$lng->get('Gender')?>:</label> <?=Features::getGender($postData['gender'])?></div>
                                        <div><label><?=$lng->get('Phone')?>:</label> <?=Format::phoneNumber($postData['phone'])?></div>
                                        <div><label><?=$lng->get('E-mail')?>:</label> <?=$postData['email']?></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="custom_block">
                        <h3 class="title"><?=$lng->get('Apartment info')?></h3>
                        <hr class="dark_purple"/>
                        <table>
                            <tr>
                                <td>
                                    <div class="sidebar_apt_photo">
                                        <?php if (file_exists(Url::uploadPath().$data['apt_info']['image'])): ?>
                                            <img src="<?= Url::filePath().'/'.$data['apt_info']['image'] ?>" title="">
                                        <?php else: ?>
                                            <img src="<?= URL::templatePath() ?>/img/no-image.png" title="">
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="padding-top: 20px;">

                                        <?php

                                        $room_name = ApartmentsModel::getRoomName($data['bed_info']['room_id']);
                                        if($room_name=='Quad'){
                                            $bg_color = '#0064eb';
                                        }elseif($room_name=='Double'){
                                            $bg_color = '#14cc94';
                                        }elseif($room_name=='Private'){
                                            $bg_color = '#f2a024';
                                        }else{
                                            $bg_color = 'gray';
                                        }
                                        if($data['bed_info']['price']<=700){
                                            $rating = '4.2';
                                        }elseif($data['bed_info']['price']<=800){
                                            $rating = '4.7';
                                        }elseif($data['bed_info']['price']<=1000){
                                            $rating = '4.9';
                                        }else{
                                            $rating = '5';
                                        }
                                        ?>

                                        <div class="apt_box_body_sidebar">
                                            <h5><?=Format::listText($data['apt_info']['title_'.$data['def_language']],18)?></h5>
                                            <div class="location"><i class="fas fa-map-marker-alt"></i> <?=Format::listText($data['apt_info']['address'], 30)?> <a href=""><?=$lng->get('Map')?></a></div>
                                            <div class="stars">
                                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                                <span class="star_point"><?=$rating?></span>
                                            </div>
                                            <div class="info">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="sub_title"><?=$lng->get('Price')?></div>
                                                        <div class="text"><?=DEFAULT_CURRENCY_SHORT?><?=Format::full_digits($data['bed_info']['price'])?></div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="sub_title"><?=$lng->get('Deposit')?></div>
                                                        <div class="text"><?=DEFAULT_CURRENCY_SHORT?><?=DEPOSIT_FEE?></div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="sub_title"><?=$lng->get('App fee')?></div>
                                                        <div class="text"><?=DEFAULT_CURRENCY_SHORT?><?=APPLICATION_FEE?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="sub_title"><?=$lng->get('Type')?></div>
                                                        <div class="text">2+4</div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="sub_title"><?=$lng->get('Gender')?></div>
                                                        <div class="text"><?=ApartmentsModel::getCategoryName($data['apt_info']['category'])?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<script>
    $(document).ready(function() {
        $(function() {
            $('#datetimepicker1').datetimepicker();
            $('#datetimepicker2').datetimepicker();
        });
    });
</script>