<?php

use Helpers\Format;
use Helpers\Url;
use Models\ApartmentsModel;
use Models\UserModel;
use Helpers\Date;
$features = explode(',', $data['item']['features']);

$landlord_info = UserModel::getInfo($item['partner_id']);
$landlord_phone = Format::phoneNumber($landlord_info['phone']);
?>


<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid remove_col_padding">

            <div class="blog_image custom_block_slider">
                <div>
                    <?php if(count($album)>0):?>
                        <div class="slider slider-for">
                        </div>
                        <div class="slider slider-nav">
                            <?php foreach($album as $photo):?>
                                <div>
                                    <img class="product_inner_more" src="<?=Url::uploadPath().'apt_album/'.$photo['id'].'/'.$photo['id'].'.jpg'?>" alt="" />
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php else:?>
                        <img id="product_inner_main_img" class="product_inner_main" src="<?=Url::filePath()?>/<?=$item['image']?>" alt="" />
                    <?php endif;?>
                </div>
            </div>

        </div>
        <div class="container">
            <div class="row paddingBottom40">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog_inner_text custom_block">

                                <div class="breadcrumsbs_in">
                                    <a href="apartments"><?=$lng->get("Apartments")?></a> /
                                    <?=$lng->get('Info')?>
                                </div>
                                <div class="apt_inner_title">
                                    <h2><?=$item['title_'.$def_language]?></h2>
                                </div>

                                <?=html_entity_decode($item['text_'.$def_language])?>
                                <div class="clearBoth"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="apt_roommates custom_block">

                                <div class="apt_inner_title">
                                    <h2><?=$lng->get('Roommates')?></h2>
                                </div><br/>
                                <div class="apt_inner_title">
                                    <?php foreach($data['rooms'] as $room_array):?>
                                        <h4><?=$room_array['name']?> <?=$lng->get('Room')?></h4>
                                        <table class="table-resonsive">
                                            <tr>
                                                    <?php
                                                        $beds = ApartmentsModel::getBeds($room_array['id']);
                                                    ?>
                                                    <?php if(count($beds)>0):?>
                                                        <?php foreach($beds as $bed):?>
                                                <td class="roommates_td">
                                                    <?php
                                                        $tenant_id = $bed['tenant_id'];
                                                        $tenant_array = UserModel::getInfo($tenant_id);
                                                        if($tenant_id>0 && $tenant_id!=267) :
                                                            $tenant_info = UserModel::getInfo($tenant_id);
                                                            ?>
                                                            <div class="rented_bed">
                                                                <img src="<?= URL::getUserImage($bed['tenant_id'], $tenant_array['gender'])?>" alt="Roommate"/><br/>
                                                                <?=$tenant_info['first_name']?>, <?=Date::dateToAge($tenant_info['birthday'])?>
                                                            </div>
                                                            <?php
                                                        elseif($bed['tenant_id']==0):?>
                                                            <div class="vacant_bed">

                                                                <?php if($data['userId']>0): ?>
                                                                <a href="apartments/apply/<?=$data['bed']['id']?>">
                                                                <?php else: ?>
                                                                <a redirect_url="apartments/apply/<?=$data['bed']['id']?>" class="umodal_toggle">
                                                                <?php endif; ?>
                                                                    <div class="vacant_bed_img">
                                                                        <i class="fa fa-user-plus"></i>
                                                                    </div>
                                                                </a>
                                                                <?=$lng->get('Vacant')?>
                                                            </div>
                                                        <?php endif;?>
                                                </td>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                            </tr>
                                        </table><hr/>
                                    <?php endforeach;?>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="container-fluid">
                        <div class="row" style="display: flex;flex-direction: column;">
                            <div class="col-md-12 sidebar_info_block remove_col_padding_web">
                                <div class="sidebar custom_block">
                                    <div class="sidebar_content_apartments">
                                        <?php

                                        $room_name = ApartmentsModel::getRoomName($data['bed']['room_id']);
                                        if($room_name=='Quad'){
                                            $bg_color = '#0064eb';
                                        }elseif($room_name=='Double'){
                                            $bg_color = '#14cc94';
                                        }elseif($room_name=='Private'){
                                            $bg_color = '#f2a024';
                                        }else{
                                            $bg_color = 'gray';
                                        }
                                        if($data['bed']['price']<=700){
                                            $rating = '4.2';
                                        }elseif($data['bed']['price']<=800){
                                            $rating = '4.7';
                                        }elseif($data['bed']['price']<=1000){
                                            $rating = '4.9';
                                        }else{
                                            $rating = '5';
                                        }
                                        ?>

                                        <div class="apt_box_body_sidebar">
                                            <h5><?=Format::listText($item['title_'.$data['def_language']],18)?></h5>
                                            <div class="location"><i class="fas fa-map-marker-alt"></i> <?=Format::listText($item['address'], 30)?> <a href=""><?=$lng->get('Map')?></a></div>
                                            <div class="stars">
                                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                                <span class="star_point"><?=$rating?></span>
                                            </div>
                                            <div class="info">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="sub_title"><?=$lng->get('Price')?></div>
                                                        <div class="text"><?=DEFAULT_CURRENCY_SHORT?><?=Format::full_digits($data['bed']['price'])?></div>
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
                                                    <div class="col-xs-8">
                                                        <div class="sub_title"><?=$lng->get('Model')?></div>
                                                        <div class="text"><?=ApartmentsModel::getModelName($item['apt_model'])?></div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="sub_title"><?=$lng->get('Gender')?></div>
                                                        <div class="text"><?=ApartmentsModel::getCategoryName($item['category'])?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="apply_link_inner">
                                            <div>

                                                <?php if($data['userId']>0): ?>
                                                    <div><a href="apartments/apply/<?=$data['bed']['id']?>" class="message_button"><?=$lng->get('Apply now')?></a></div>
                                                    <div><a class="message_button" href="apartments/showing/<?=$data['bed']['id']?>"><?=$lng->get('Schedule Showing')?></a></div>
                                                    <div><a href="message/<?=$item['partner_id']?>" class="chat_button"><?=$lng->get('Live chat')?></a></div>
                                                <?php else: ?>
                                                    <div><a redirect_url="apartments/apply/<?=$data['bed']['id']?>" class="umodal_toggle message_button"><?=$lng->get('Apply now')?></a></div>
                                                    <div><a redirect_url="apartments/showing/<?=$data['bed']['id']?>" class="umodal_toggle message_button"><?=$lng->get('Schedule Showing')?></a></div>
                                                    <div><a redirect_url="message/<?=$item['partner_id']?>" class="umodal_toggle chat_button"><?=$lng->get('Live chat')?></a></div>
                                                <?php endif; ?>

                                            </div>
                                            <div class="sidebar_call_us">
                                                <span class="sidebar_call_us_label">or Call</span> <a href="tel:<?=$landlord_phone?>"><?=$landlord_phone?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if(strlen($item['map_address'])>10):?>
                            <div class="col-md-12 sidebar_model_block remove_col_padding_web">
                                <div class="sidebar custom_block">
                                    <iframe src="<?=$item['map_address']?>" width="100%" height="400px" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                                </div>
                            </div>
                            <?php endif;?>

                            <?php if(count($features)>1):?>
                            <div class="col-md-12 sidebar_amenities_block remove_col_padding_web">
                                <div class="sidebar custom_block">
                                    <h4><?=$lng->get('Amenities')?></h4>
                                    <ul>
                                        <?php $c=1;foreach ($features as $feature):?>
                                            <li><i class="fas fa-check-square"></i> <?=ApartmentsModel::getFeatureName($feature)?></li>
                                            <?php if($c>=30): ?>
                                                <li><i class="fas fa-check-square"></i> <?=$lng->get("And more")?>...</li>
                                            <?php break;endif; ?>
                                        <?php $c++;endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="umodal login">
    <div class="umodal_box">
        <div class="umodal_head">
            <div class="umodal_title"><h2 class="title" id="umodal_title"><?=$lng->get('Register')?></h2></div>
            <div class="umodal_close"><i class="fas fa-times"></i></div>
            <div class="clearBoth"></div>
            <hr class="dark_gray"/>
        </div>
        <div class="umodal_body">
            <?php require $data['modal_url'];?>
        </div>
<!--        <div id="redirect_url">-->
<!---->
<!--        </div>-->
    </div>
</div>

<script>
    $('.vacant_bed_img').hover(
        function() {
            var $this = $(this); // caching $(this)
            $this.data('defaultText', $this.html());
            var text = '<?= $lng->get('Apply Now!')?>';
            $this.html('<div style="margin-top:13px;font-size: 15px;">'+text+'</div>');
        },
        function() {
            var $this = $(this); // caching $(this)
            $this.html($this.data('defaultText'));
        }
    );
</script>