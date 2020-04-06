<?php

use Helpers\Csrf;
use Helpers\Url;
use Helpers\Format;
use Modules\admin\Models\RoomsModel;
use Helpers\Features;
use Helpers\Date;
use Models\RoommatesModel;

?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row inner_background">
                <div class="col-sm-12">
                    <h1 class="page_title"><?=$data['page_title']?></h1>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="container-fluid">
                        <div class="row paddingTop20 paddingBottom40">
                            <div class="col-sm-3">
                                <div class="default forum_sidebar apartment_search_box">
                                    <div class="forum_sidebar_title_mob">
                                        <div style="float: left"><?=$lng->get('Filter')?></div>
                                        <i class="fa fa-times forum_sidebar_close visible-xs" style="float: right"></i>
                                        <div class="clearBoth"></div>
                                    </div>
                                    <form action="" method="post">
                                        <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                                        <button type="submit" class="btn apartment_button" style="width: 100%"><i class="fa fa-sync-alt"></i> <?=$lng->get('Show')?></button>
                                        <div class="reset_filter">
                                            <a href="roommates?reset_filter"><i class="fa fa-eraser"></i> <?=$lng->get('Reset filter')?></a>
                                        </div>



                                        <style>
                                            .dropbtn {
                                                background-color: #4CAF50;
                                                color: white;
                                                padding: 16px;
                                                font-size: 16px;
                                                border: none;
                                                cursor: pointer;
                                            }

                                            .dropbtn:hover, .dropbtn:focus {
                                                background-color: #3e8e41;
                                            }

                                            .location_input {
                                                box-sizing: border-box;
                                                background-position: 14px 12px;
                                                background-repeat: no-repeat;
                                                font-size: 16px;
                                                padding: 14px 20px 12px 45px;
                                                margin-bottom:0!important;
                                            }

                                            .dropdown {
                                                /*position: relative;*/
                                                /*display: inline-block;*/
                                            }

                                            .dropdown-content {
                                                display: none;
                                                background-color: #f6f6f6;
                                                overflow: auto;
                                                border-left: 1px solid #ddd;
                                                border-right: 1px solid #ddd;
                                                border-bottom: 1px solid #ddd;
                                                z-index: 1;
                                            }

                                            .dropdown-content ul {
                                                margin:0;
                                                padding:0;
                                            }

                                            .dropdown-content li {
                                                color: black;
                                                padding: 12px 16px!important;
                                                text-decoration: none;
                                                display: block;
                                                cursor: pointer;
                                            }

                                            .dropdown li:hover {background-color: #ddd;}

                                            .show {display: block;}
                                        </style>
                                        <h4><?=$lng->get('Location')?>:</h4>
                                        <div class="apartment_search_box_subarea_no_mb">

                                            <div class="dropdown">
                                                <input class="location_input" category="" name="location" placeholder="Type Location" value="<?=$postData['location']?>" type="text" id="search_location_input">
                                                <div id="locationDropDown" class="dropdown-content">
                                                </div>
                                            </div>
                                        </div>
                                        <h4><?=$lng->get('Move in date')?>:</h4>
                                        <div class="apartment_search_box_subarea">
                                                <select class="same_line_select_small" name="movein_month" required>
                                                    <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                        <option <?=($postData['movein_month'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <select class="same_line_select_small" name="movein_day" required>
                                                    <?php if($postData['movein_day']==$key){$selected='selected';}else{$selected='';}?>
                                                    <?php foreach (Date::getDays() as $day):?>
                                                        <option <?=($postData['movein_day'] == $day)?'selected':''?> value="<?= $day ?>"><?= $day ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <select class="same_line_select_small" name="movein_year" required>
                                                    <?php $max_years = date("Y")+1; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                        <option <?=($postData['movein_year'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                        </div>

                                        <h4><?=$lng->get('Stay length')?>:</h4>
                                        <div class="apartment_search_box_subarea">
                                            <select class="same_line_select" name="stay_min" required>
                                                <option <?=($postData['stay_min'] == 0)?'selected':''?> value="0">No min</option>
                                                <option <?=($postData['stay_min'] == 1)?'selected':''?> value="1">1 <?=$lng->get('month')?></option>
                                                <?php for ($i=2;$i<=24;$i++):?>
                                                    <option <?=($postData['stay_min'] == $i)?'selected':''?> value="<?= $i ?>"><?= $i ?> <?=$lng->get('months')?></option>
                                                <?php endfor; ?>
                                            </select>-&nbsp;&nbsp;

                                            <select class="same_line_select" name="stay_max" required>
                                                <option <?=($postData['stay_max'] == 0)?'selected':''?> value="0">No max</option>
                                                <option <?=($postData['stay_max'] == 1)?'selected':''?> value="1">1 <?=$lng->get('month')?></option>
                                                <?php for ($i=2; $i<=24; $i++):?>
                                                    <option <?=($postData['stay_max'] == $i)?'selected':''?> value="<?= $i ?>"><?= $i ?> <?=$lng->get('months')?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>


                                        <h4><?=$lng->get('Budget range')?>:</h4>
                                        <div class="apartment_search_box_subarea">
                                            <div class="range_values">
                                                <span id="spanRange1"><?=$postData['budget_min']?> <?=DEFAULT_CURRENCY?></span>
                                                <span id="spanRange2" style="float:right;"><?=$postData['budget_max']?><?=($postData['budget_max']==$data['filter_max'])?'+':''?> <?=DEFAULT_CURRENCY?></span>
                                            </div>
                                            <div class="range">
                                                <div class="clearBoth"></div>
                                                <div class="range_line">
                                                    <div class="range_inputs">
                                                        <input name="budget_min" type="range" value="<?=$postData['budget_min']?>" min="0" max="<?=$data['filter_max']?>" id="range1" class="range1"/>
                                                        <input name="budget_max" type="range" value="<?=$postData['budget_max']?>" min="10" max="<?=$data['filter_max']?>" id="range2" class="range2"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="budget_period"><?=$lng->get('Per')?>
                                                <label><input type="radio" name="budget_period" value="1" <?=($postData['budget_period'] == 1)?'checked':''?>/> <?=$lng->get('day')?></label>
                                                <label><input type="radio" name="budget_period" value="2" <?=($postData['budget_period'] == 2)?'checked':''?>/> <?=$lng->get('week')?></label>
                                                <label><input type="radio" name="budget_period" value="3" <?=($postData['budget_period'] == 3)?'checked':''?>/> <?=$lng->get('month')?></label>
                                            </div>
                                        </div>
                                        <div class="clearBoth"></div>
                                        <div class="default">
                                            <div class="apartment_search_box_subarea">
                                                <h4><?=$lng->get('Gender')?>:</h4>
                                                <?php foreach ($data['gender_list'] as $key=>$val):?>
                                                    <label class="checkbox"><?=$val?>
                                                        <input type="checkbox" <?=(in_array($key, $postData['gender']))?'checked':''?> name="gender[<?=$key?>]" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>


                                            <div class="apartment_search_box_subarea">
                                                <h4><?=$lng->get('Profession')?>:</h4>
                                                <?php foreach ($data['profession_list'] as $key=>$val):?>
                                                    <label class="checkbox"><?=$val?>
                                                        <input type="checkbox" <?=(in_array($key, $postData['profession']))?'checked':''?> name="profession[<?=$key?>]" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>


                                            <div class="apartment_search_box_subarea_no_mb">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <label><?=$lng->get('Smoking')?>:</label>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <select name="smoking" required>
                                                            <option <?=($postData['smoking'] == 0)?'selected':''?> value="0"><?=$lng->get('Don\'t mind')?></option>
                                                            <option <?=($postData['smoking'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                                            <option <?=($postData['smoking'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="apartment_search_box_subarea_no_mb">
                                                <div class="row">
                                                    <div class="col-xs-6" style="vertical-align: middle">
                                                        <label><?=$lng->get('Pets')?>:</label>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <select name="animals" required>
                                                            <option <?=($postData['animals'] == 0)?'selected':''?> value="0"><?=$lng->get('Don\'t mind')?></option>
                                                            <option <?=($postData['animals'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                                            <option <?=($postData['animals'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="apartment_search_box_subarea_no_mb">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <label><?=$lng->get('Language')?>:</label>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <select name="language" required>
                                                            <?php foreach ($languageList as $key=> $value): if($postData['language']==$key){$selected='selected';}else{$selected='';}?>
                                                                <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="apartment_search_box_subarea_no_mb">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <label><?=$lng->get('Nationality')?>:</label>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <select name="nationality" required>
                                                            <?php foreach ($nationalityList as $key=> $value): if($postData['nationality']==$key){$selected='selected';}else{$selected='';}?>
                                                                <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="apartment_search_box_subarea">
                                                <h4><?=$lng->get('Amenities')?>:</h4>
                                                <?php foreach ($data['feature_list'] as $list):?>
                                                    <label class="checkbox"><?=$list['title_'.$data['def_language']]?>
                                                        <input type="checkbox" <?=(in_array('f'.$list['id'], $postData['features']))?'checked':''?> name="features[f<?=$list['id']?>]" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>


                                        <input type="hidden" name="filter"/>
                                        <button type="submit" class="btn apartment_button" style="width: 100%"><i class="fa fa-sync-alt"></i> <?=$lng->get('Show')?></button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-sm-9">
                                <div class="default filter_buttons">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <form action="" method="post">
                                                <?=$lng->get('Order')?>:
                                                <select name="products_order" style="width:140px"onchange="this.form.submit()">
                                                    <option value="last"<?=($data['products_order']=='last')?'selected':''?>><?=$lng->get('Recent')?></option>
                                                    <option value="low_price" <?=($data['products_order']=='low_price')?'selected':''?>><?=$lng->get('Price: Low to High')?></option>
                                                    <option value="high_price" <?=($data['products_order']=='high_price')?'selected':''?>><?=$lng->get('Price: High to Low')?></option>
                                                </select>
                                                <a class="add_listing" href="roommates/add" title="<?=$lng->get('Add Listing')?>"><i class="fa fa-plus"></i> <?=$lng->get('Add Listing')?></a>
                                            </form>
                                            <button class="forum_sidebar_toggle btn default_button"><?=$lng->get('Filter')?> <i class="fa fa-caret-down"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $list_count = count($data['list']);
//                                \Helpers\Console::varDump($data['list']);
                                if($list_count>0):
                                    foreach ($data['list'] as $list):
                                        $list['features'] = explode(',',$list['features']);

                                        ?><div class="apartments_box" style="display: table;width:100%;">
                                        <div class="row">

                                            <div class="col-sm-12 apartments_content remove_col_padding_mob">

                                                <div class="row">
                                                    <div class="col-md-3 remove_col_padding_mob" style="display: table-cell;width: 160px">
                                                        <div class="roommate_img">
                                                            <img src="<?=Url::getUserImage($list['user_id'])?>" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9 roommate_list_info remove_col_padding_mob">
                                                        <div class="roommate_list_title">
                                                            <h3>
                                                                <?=Format::listTitle($list['first_name'])?>,
                                                                <?=DEFAULT_CURRENCY_SHORT?><?=Format::full_digits($list['budget'])?>/<?=Features::getBudgetPeriod($list['budget_period'])?>
                                                            </h3>

                                                            <div class="roommate_list_subtitle">

                                                                <?=Features::getGender($list['gender'])?>
                                                                <?=Features::getProfession($list['profession'])?>,
                                                                <?=Date::dateToAge($list['birthday'])?> <?=$lng->get("years")?>
                                                            </div>
                                                        </div>
                                                        <div class="roommmate_list_area">
                                                            <div class="hidden-xs">
                                                                <span><?=$lng->get("Area")?>:</span> <?=RoommatesModel::getLocationName($list['state_id'],$list['county_id'],$list['city_id'])?><br/>
                                                                <span><?=$lng->get("Available")?>:</span> <?=Date::dateToDayMonth($list['movein_date'])?><br/>
                                                                <span><?=$lng->get('Last active')?>: </span> <?=Date::getOnline($list['time'])?>
                                                            </div>
                                                            <div class="visible-xs">
                                                                <div class="roommmate_list_area_row"><i class="fa fa-map-marker-alt"></i> <?=RoommatesModel::getLocationName($list['state_id'],$list['county_id'],$list['city_id'])?></div>
                                                                <div class="roommmate_list_area_row"><i class="fa fa-clock"></i> <?=Date::getOnline($list['time'])?></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 remove_col_padding_mob">
                                                        <div class="roommmate_description"><?=Format::listText($list['description'],100)?></div>
                                                    </div>
                                                </div>

                                                <div class="clearBoth"></div>
                                            </div>
                                            <div class="col-sm-12 remove_col_padding_mob">
                                                <a href="roommate/<?=$list['id']?>/<?=Format::urlText($list['first_name'])?>" class="btn apartment_button_view"><?=$lng->get('View Details')?></a>
                                                <a href="message/<?=$list['user_id']?>" class="btn apartment_button"><i class="fa fa-paper-plane" style="color:#fff;"></i> <?=$lng->get('Send Message')?></a>
                                            </div>
                                        </div>
                                    </div><?php
                                    endforeach;
                                else:
                                    ?>
                                    <div class="full_occupancy">
                                        <?=$lng->get('Sorry, We have no roommate listing for this criteria.<br/>
                                        Please, try to change filter parameters and search again.')?>
                                    </div><?php
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    var range1 = document.getElementById("range1");
    var range2 = document.getElementById("range2");
    var spanRange1 = document.getElementById("spanRange1");
    var spanRange2 = document.getElementById("spanRange2");
    range1.onchange = function() {
        spanRange1.innerHTML = this.value+' <?=DEFAULT_CURRENCY;?>';
    }
    range2.onchange = function() {
        if(this.value=='2000'){
            spanRange2.innerHTML = this.value+'+ <?=DEFAULT_CURRENCY;?>';
        }else {
            spanRange2.innerHTML = this.value + ' <?=DEFAULT_CURRENCY;?>';
        }
    }
</script>
