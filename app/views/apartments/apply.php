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
                    <h1 class="page_title"><?=$lng->get('Apply for Apartment')?></h1>
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
                                    <?=$lng->get('Apply')?>
                                </div>

                                <form action="" method="POST">
                                    <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                                    <input type="hidden" value="<?=$postData['apt_id']?>" name="apt_id" />
                                    <input type="hidden" value="<?=$postData['room_id']?>" name="room_id" />
                                    <input type="hidden" value="<?=$postData['bed_id']?>" name="bed_id" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label><?=$lng->get('Desired Move in Date')?></label> <span class="required_star">*</span><br/>
                                            <select class="same_line_select" name="movein_month" required>
                                                <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                    <option <?=($postData['movein_month'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>


                                            <select class="same_line_select" name="movein_day" required>
                                                <?php if($postData['movein_day']==$key){$selected='selected';}else{$selected='';}?>
                                                <?php foreach (Date::getDays() as $day):?>
                                                    <option <?=($postData['movein_day'] == $day)?'selected':''?> value="<?= $day ?>"><?= $day ?></option>
                                                <?php endforeach; ?>
                                            </select>


                                            <select class="same_line_select" name="movein_year" required>
                                                <?php $max_years = date("Y")+1; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                    <option <?=($postData['movein_year'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <h3 class="title"><?=$lng->get('Personal info')?></h3>
                                        <hr class="dark_purple"/>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('SSN')?> <span class="required_star">*</span>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="ssn" type="text" placeholder="SSN" class="same_line_input" value="<?=$postData['ssn']?>" required/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Drive license')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="dl" type="text" placeholder="<?=$lng->get('Driver License')?>" class="same_line_input" value="<?=$postData['dl']?>"/>

                                            <select class="same_line_select_state" name="dl_state">
                                                <option <?=($postData['dl_state'] == 0)?'selected':''?> value="0">---</option>
                                                <?php foreach ($data['state_list'] as $state):?>
                                                    <option <?=($postData['dl_state'] == $state['id'])?'selected':''?> value="<?=$state['id']?>"><?=$state['state_code']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>


<!--                                    CURRENT RESIDENTIAL INFO-->
                                    <div class="row">
                                        <h3 class="title"><?=$lng->get('Current Residential info')?></h3>
                                        <hr class="dark_purple"/>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Current Address')?> <span class="required_star">*</span>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="current_address" type="text" placeholder="<?=$lng->get('Address')?>" value="<?=$postData['current_address']?>" required/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">

                                        </div>
                                        <div class="col-xs-8">
                                            <input name="current_city" type="text" placeholder="<?=$lng->get('City')?>" class="same_line_input" value="<?=$postData['current_city']?>" required/>
                                            <input name="current_zip" type="text" placeholder="<?=$lng->get('Zip code')?>" class="same_line_input" value="<?=$postData['current_zip']?>" required/>

                                            <select class="same_line_select_state" name="current_state" required>
                                                <option <?=($postData['current_state'] == 0)?'selected':''?> value="0">---</option>
                                                <?php foreach ($data['state_list'] as $state):?>
                                                    <option <?=($postData['current_state'] == $state['id'])?'selected':''?> value="<?=$state['id']?>"><?=$state['state_code']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Country')?> <span class="required_star">*</span>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select name="current_country" required>
                                                <?php if($postData['current_country']==$key){$selected='selected';}else{$selected='';}?>
                                                <?php foreach ($data['countryList'] as $key=>$value):?>
                                                    <option <?=($postData['current_country'] == $key)?'selected':''?> value="<?=$key?>"><?=$value?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Monthly Rent')?> <span class="required_star">*</span>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <?=DEFAULT_CURRENCY_SHORT?> <input name="current_rent" type="text" placeholder="<?=$lng->get('Rent')?>" value="<?=$postData['current_rent']?>" class="same_line_input" required/>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Lived from')?></label> <span class="required_star">*</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="current_month_from" required>
                                                <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                    <option <?=($postData['current_month_from'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select class="same_line_select" name="current_year_from" required>
                                                <?php $max_years = date("Y")-50; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                    <option <?=($postData['current_year_from'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Lived to')?></label> <span class="required_star">*</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="current_month_to" required>
                                                <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                    <option <?=($postData['current_month_to'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select class="same_line_select" name="current_year_to" required>
                                                <?php $max_years = date("Y")-50; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                    <option <?=($postData['current_year_to'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Landlord')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                             <input name="current_landlord_name" type="text" placeholder="<?=$lng->get('Landlord name')?>" value="<?=$postData['current_landlord_name']?>"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label></label>
                                        </div>
                                        <div class="col-xs-8">
                                             <input name="current_landlord_phone" type="text" placeholder="<?=$lng->get('Landlord phone')?>" value="<?=$postData['current_landlord_phone']?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label></label>
                                        </div>
                                        <div class="col-xs-8">
                                             <input name="current_landlord_email" type="text" placeholder="<?=$lng->get('Landlord e-mail')?>" value="<?=$postData['current_landlord_email']?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Move Out Reason')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <textarea rows="3" name="current_reason" placeholder="<?=$lng->get('Move Out Reason')?>"><?=$postData['current_reason']?></textarea>
                                        </div>
                                    </div>

<!--                                    PREVIOUS RESIDENTIAL INFO-->
                                    <div class="row">
                                        <h3 class="title"><?=$lng->get('Previous Residential info')?></h3>
                                        <hr class="dark_purple"/>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Previous Address')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="previous_address" type="text" placeholder="<?=$lng->get('Address')?>" value="<?=$postData['previous_address']?>"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">

                                        </div>
                                        <div class="col-xs-8">
                                            <input name="previous_city" type="text" placeholder="<?=$lng->get('City')?>" class="same_line_input" value="<?=$postData['previous_city']?>"/>
                                            <input name="previous_zip" type="text" placeholder="<?=$lng->get('Zip code')?>" class="same_line_input" value="<?=$postData['previous_zip']?>"/>

                                            <select class="same_line_select_state" name="previous_state">
                                                <option <?=($postData['previous_state'] == 0)?'selected':''?> value="0">---</option>
                                                <?php if($postData['previous_state']==$key){$selected='selected';}else{$selected='';}?>
                                                <?php foreach ($data['state_list'] as $state):?>
                                                    <option <?=($postData['previous_state'] == $state['id'])?'selected':''?> value="<?=$state['id']?>"><?=$state['state_code']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Country')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select name="previous_country">
                                                <?php if($postData['previous_country']==$key){$selected='selected';}else{$selected='';}?>
                                                <?php foreach ($data['countryList'] as $key=>$value):?>
                                                    <option <?=($postData['previous_country'] == $key)?'selected':''?> value="<?=$key?>"><?=$value?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Monthly Rent')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <?=DEFAULT_CURRENCY_SHORT?> <input name="previous_rent" type="text" placeholder="<?=$lng->get('Rent')?>" value="<?=$postData['previous_rent']?>" class="same_line_input"/>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Lived from')?></label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="previous_month_from">
                                                <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                    <option <?=($postData['previous_month_from'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select class="same_line_select" name="previous_year_from">
                                                <?php $max_years = date("Y")-50; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                    <option <?=($postData['previous_year_from'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Lived to')?></label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="previous_month_to">
                                                <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                    <option <?=($postData['previous_month_to'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select class="same_line_select" name="previous_year_to">
                                                <?php $max_years = date("Y")-50; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                    <option <?=($postData['previous_year_to'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Landlord')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                             <input name="previous_landlord_name" type="text" placeholder="<?=$lng->get('Landlord name')?>" value="<?=$postData['previous_landlord_name']?>"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label></label>
                                        </div>
                                        <div class="col-xs-8">
                                             <input name="previous_landlord_phone" type="text" placeholder="<?=$lng->get('Landlord phone')?>" value="<?=$postData['previous_landlord_phone']?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label></label>
                                        </div>
                                        <div class="col-xs-8">
                                             <input name="previous_landlord_email" type="text" placeholder="<?=$lng->get('Landlord e-mail')?>" value="<?=$postData['previous_landlord_email']?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Move Out Reason')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <textarea rows="3" name="previous_reason" placeholder="<?=$lng->get('Move Out Reason')?>"><?=$postData['previous_reason']?></textarea>
                                        </div>
                                    </div>




<!--                                    EMPLOYMENT INFO-->
                                    <div class="row">
                                        <h3 class="title"><?=$lng->get('Employment / Income info')?></h3>
                                        <hr class="dark_purple"/>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Employer Address')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="employer_address" type="text" placeholder="<?=$lng->get('Address')?>" value="<?=$postData['employer_address']?>"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">

                                        </div>
                                        <div class="col-xs-8">
                                            <input name="employer_city" type="text" placeholder="<?=$lng->get('City')?>" class="same_line_input" value="<?=$postData['employer_city']?>"/>
                                            <input name="employer_zip" type="text" placeholder="<?=$lng->get('Zip code')?>" class="same_line_input" value="<?=$postData['employer_zip']?>"/>

                                            <select class="same_line_select_state" name="employer_state">
                                                <option <?=($postData['employer_state'] == 0)?'selected':''?> value="0">---</option>
                                                <?php if($postData['employer_state']==$key){$selected='selected';}else{$selected='';}?>
                                                <?php foreach ($data['state_list'] as $state):?>
                                                    <option <?=($postData['employer_state'] == $state['id'])?'selected':''?> value="<?=$state['id']?>"><?=$state['state_code']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Country')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select name="employer_country" required>
                                                <?php if($postData['employer_country']==$key){$selected='selected';}else{$selected='';}?>
                                                <?php foreach ($data['countryList'] as $key=>$value):?>
                                                    <option <?=($postData['employer_country'] == $key)?'selected':''?> value="<?=$key?>"><?=$value?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Annual Salary')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <?=DEFAULT_CURRENCY_SHORT?> <input name="salary" type="text" placeholder="<?=$lng->get('Salary')?>" value="<?=$postData['salary']?>" class="same_line_input"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Position')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="position" type="text" placeholder="<?=$lng->get('Position')?>" value="<?=$postData['position']?>"/>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Worked from')?></label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="worked_month_from">
                                                <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                    <option <?=($postData['worked_month_from'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select class="same_line_select" name="worked_year_from">
                                                <?php $max_years = date("Y")-50; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                    <option <?=($postData['worked_year_from'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Worked to')?></label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="worked_month_to">
                                                <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                                    <option <?=($postData['worked_month_to'] == $month)?'selected':''?> value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select class="same_line_select" name="worked_year_to">
                                                <?php $max_years = date("Y")-50; foreach (Date::getYears(date('Y'),$max_years) as $year): ?>
                                                    <option <?=($postData['worked_year_to'] == $year)?'selected':''?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Supervisor')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="employer_name" type="text" placeholder="<?=$lng->get('Supervisor name')?>" value="<?=$postData['employer_name']?>"/>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label></label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="employer_phone" type="text" placeholder="<?=$lng->get('Supervisor phone')?>" value="<?=$postData['employer_phone']?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label></label>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="employer_email" type="text" placeholder="<?=$lng->get('Supervisor e-mail')?>" value="<?=$postData['employer_email']?>" />
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Extra Annual Income')?>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <?=DEFAULT_CURRENCY_SHORT?> <input name="extra_income" type="text" placeholder="<?=$lng->get('Extra income')?>" value="<?=$postData['extra_income']?>" class="same_line_input"/>
                                        </div>
                                    </div>


<!--Additional info-->
                                    <div class="row">
                                        <h3 class="title"><?=$lng->get('Additional info')?></h3>
                                        <hr class="dark_purple"/>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Do you smoke?')?> <span class="required_star">*</span>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="smoking" required>
                                                <option <?=($postData['smoking'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                                <option <?=($postData['smoking'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label><?=$lng->get('Do you have any animal or pets?')?> <span class="required_star">*</span>:</label>
                                        </div>
                                        <div class="col-xs-8">
                                            <select class="same_line_select" name="animals" required>
                                                <option <?=($postData['animals'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                                <option <?=($postData['animals'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label><?=$lng->get('Notes')?>:</label>
                                            <textarea rows="5" name="note" placeholder="Notes (not necessary)"><?=$postData['note']?></textarea>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Apply')?></button>
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