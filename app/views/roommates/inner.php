<?php

use Helpers\Date;
use Helpers\Features;
use Helpers\Url;
use Helpers\Format;
use Models\ApartmentsModel;
use Models\RoommatesModel;

?>


<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><?=$item['first_name']?>,
                <?=Features::getGender($item['gender'])?>
                <?=Features::getProfession($item['profession'])?>,
                <?=Date::dateToAge($item['birthday'])?> <?=$lng->get("years")?></h3>
        </div>
        <div class="container">
            <div class="row paddingBottom40">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="breadcrumbs">
                                <a href="roommates"><?=$lng->get("Roommates")?></a> /
                                <?=$item['first_name']?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom_block">
                                <div class="blog_image">
                                    <div>
                                        <img id="product_inner_main_img" class="product_inner_main" src="<?=Url::getUserImage($item['user_id'])?>" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(strlen($item['description'])>10):?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom_block">
                                    <?=html_entity_decode($item['description'])?>
                                    <div class="clearBoth"></div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom_block">
                                <h4><?=$item['first_name']?><?=$lng->get('\'s')?> <?=$lng->get('Details')?></h4>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Age')?>:</div>
                                    <div class="col-xs-6"><?=Date::dateToAge($item['birthday'])?> <?=$lng->get("years")?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Gender')?>:</div>
                                    <div class="col-xs-6"><?=Features::getGender($item['gender'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Profession')?>:</div>
                                    <div class="col-xs-6"><?=Features::getProfession($item['profession'])?></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Desired Move in')?>:</div>
                                    <div class="col-xs-6"><?=Date::dateToDayMonth($item['movein_date'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Stay period')?>:</div>
                                    <div class="col-xs-6"><?=$item['stay_min']?> <?=$lng->get("to")?> <?=$item['stay_min']?> <?=$lng->get("months")?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Budget')?>:</div>
                                    <div class="col-xs-6"><?=DEFAULT_CURRENCY_SHORT?><?=Format::full_digits($item['budget'])?>/<?=Features::getBudgetPeriod($item['budget_period'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Smoke')?>:</div>
                                    <div class="col-xs-6"><?=Features::getSmoke($item['smoking'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Pets or Animals')?>:</div>
                                    <div class="col-xs-6"><?=Features::getAnimals($item['animals'])?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom_block">
                                <h4><?=$lng->get('Wants to live with')?></h4>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Preferred Gender')?>:</div>
                                    <div class="col-xs-6"><?=Features::getGenderPr($item['pr_gender'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Profession')?>:</div>
                                    <div class="col-xs-6"><?=Features::getProfessionPr($item['pr_profession'])?></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Smoking')?>:</div>
                                    <div class="col-xs-6"><?=Features::getSmokePr($item['pr_smoking'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Pets or Animals')?>:</div>
                                    <div class="col-xs-6"><?=Features::getAnimalsPr($item['pr_animals'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Preferred Language')?>:</div>
                                    <div class="col-xs-6"><?=Features::getLanguages($item['language'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Preferred Nationality')?>:</div>
                                    <div class="col-xs-6"><?=Features::getNationalities($item['nationality'])?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"><?=$lng->get('Preferred Area')?>:</div>
                                    <div class="col-xs-6"><?=RoommatesModel::getLocationName($item['state_id'],$item['county_id'],$item['city_id'])?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php

                    if(!empty($data['item']['features'])):
                        $features = explode(',', $data['item']['features']);
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sidebar_amenities_block sidebar custom_block">
                                <h4><?=$lng->get('Preferred Amenities')?></h4>
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
                    </div>
                    <?php endif;?>
                </div>
                <div class="col-md-4">
                    <div class="container-fluid">
                        <div class="row" style="display: flex;flex-direction: column;">
                            <div class="col-md-12 sidebar_info_block remove_col_padding_web">
                                <div class="sidebar custom_block">
                                <div class="sidebar_content_apartments">
                                    <h3><span class="apartment_name"><?=$item['first_name']?></span></h3>
                                    <?=Features::getGender($item['gender'])?>
                                    <?=Features::getProfession($item['profession'])?>,
                                    <?=Date::dateToAge($item['birthday'])?> <?=$lng->get("years")?></h3>
                                    <div class="apartment_price">
                                        <span class="price_inner"><?=DEFAULT_CURRENCY_SHORT?><?=Format::full_digits($item['budget'])?>/<?=Features::getBudgetPeriod($item['budget_period'])?></span>
                                    </div>
                                    <div style="margin-bottom:15px">
                                        <span><?=$lng->get("Area")?>:</span> <?=RoommatesModel::getLocationName($item['state_id'],$item['county_id'],$item['city_id'])?><br/>
                                        <span><?=$lng->get("Available")?>: </span> <?=Date::dateToDayMonth($item['movein_date'])?>
                                    </div>
                                    <div class=""><a href="message/<?=$item['user_id']?>" class="message_button"><i class="fa fa-paper-plane" style="color:#fff;"></i> <?=$lng->get("Send message")?></a> </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
