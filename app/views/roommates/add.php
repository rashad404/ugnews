<?php
use Helpers\Csrf;
use Helpers\Date;
use Helpers\Url;
use Helpers\Features;
use Helpers\Format;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row inner_background">
                <div class="col-sm-12">
                    <h1 class="page_title"><?=$lng->get('Add Roommate listing')?></h1>
                </div>
            </div>
        </div>
        <div class="container-fluid default add_listing">
            <div class="row paddingBottom40">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-6">
                    <!--About your ad-->
                    <div class="col-sm-12">
                        <h1 class="title"><?=$lng->get('About your ad')?></h1>
                        <hr class="dark_purple"/>
                    </div>

                <form action="" method="POST">
                        <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                        <div class="col-sm-12">

                            <div class="row">
                                <div class="col-xs-12">

                                    <div class="form-group">
                                        <label><?=$lng->get('Desired Location')?></label><br/>
                                        <select class="same_line_select" name="state_id" id="state" required>
                                            <option value="0"><?=$lng->get('Select State')?></option>
                                            <?php foreach ($data['state_list'] as $list):?>
                                                <option value="<?=$list['id']?>"><?=$list['state_code']?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <select style="display:none" class=" same_line_select" name="county_id" id="county"></select>

                                        <select style="display:none" class="same_line_select" name="city_id" id="city"></select>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <label><?=$lng->get('Your Budget')?></label><br/>
                                    <?=DEFAULT_CURRENCY_SHORT?> <input class="same_line_input" name="budget" placeholder="<?=$lng->get('Budget')?>" type="text" value="<?=$postData['budget']?>">


                                    <select class="same_line_select" name="budget_period" required>
                                        <option <?=($postData['budget_period'] == 0)?'selected':''?> value="0"><?=$lng->get('---')?></option>
                                        <option <?=($postData['budget_period'] == 1)?'selected':''?> value="1"><?=$lng->get('Per Day')?></option>
                                        <option <?=($postData['budget_period'] == 2)?'selected':''?> value="2"><?=$lng->get('Per Week')?></option>
                                        <option <?=($postData['budget_period'] == 3)?'selected':''?> value="3"><?=$lng->get('Per Month')?></option>
                                    </select>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><?=$lng->get('Desired Move in Date')?></label><br/>
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
                                <div class="col-md-12">
                                    <label><?=$lng->get('I will stay')?></label><br/>

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
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label><?=$lng->get('Preferred amenities')?></label><br/>

                                    <?php $i=0;foreach ($data['features_list'] as $list):
                                        $total_list = count($data['features_list']);
                                        $half_list = floor($total_list/2);
                                        if($i==$half_list+1){
                                            ?></div><div class="col-md-6"><label>&nbsp;</label><br/><?php
                                        }
                                        ?>
                                        <label class="checkbox"><?=$list['title_'.$data['def_language']]?>
                                            <input type="checkbox" <?=(key_exists($list['id'], $postData['features']))?'checked':''?> name="features[<?=$list['id']?>]" value="1">
                                            <span style="border: 1px solid rgb(216, 218, 217);" class="checkmark"></span>
                                        </label>
                                    <?php $i++; endforeach; ?>
                                </div>
                            </div>
                        </div>

                    <!--About yourself-->
                    <div class="col-sm-12">
                        <h1 class="title"><?=$lng->get('About yourself')?></h1>
                        <hr class="dark_purple"/>
                    </div>


                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Profession')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="profession" required>
                                <option <?=($postData['profession'] == 0)?'selected':''?> value="0"><?=$lng->get('---')?></option>
                                <option <?=($postData['profession'] == 1)?'selected':''?> value="1"><?=$lng->get('Student')?></option>
                                <option <?=($postData['profession'] == 2)?'selected':''?> value="2"><?=$lng->get('Professional')?></option>
                                <option <?=($postData['profession'] == 3)?'selected':''?> value="3"><?=$lng->get('Other')?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Do you smoke?')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="smoking" required>
                                <option <?=($postData['smoking'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                <option <?=($postData['smoking'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Do you have any animal or pets?')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="animals" required>
                                <option <?=($postData['animals'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                <option <?=($postData['animals'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Preferred language')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="language" required>
                                <?php foreach ($languageList as $key=> $value): if($postData['language']==$key){$selected='selected';}else{$selected='';}?>
                                    <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Your nationality')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="nationality" required>
                                <?php foreach ($nationalityList as $key=> $value): if($postData['nationality']==$key){$selected='selected';}else{$selected='';}?>
                                    <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!--Preferred roommate-->
                    <div class="col-sm-12">
                        <h1 class="title"><?=$lng->get('Preferred roommate')?></h1>
                        <hr class="dark_purple"/>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Gender')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="pr_gender" required>
                                <option <?=($postData['pr_gender'] == 0)?'selected':''?> value="0"><?=$lng->get('Don\'t mind')?></option>
                                <option <?=($postData['pr_gender'] == 1)?'selected':''?> value="1"><?=$lng->get('Male')?></option>
                                <option <?=($postData['pr_gender'] == 2)?'selected':''?> value="2"><?=$lng->get('Female')?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Age range')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" style="width: 100px!important;" name="pr_age_min" required>
                                <option <?= $selected ?> value="0">---</option>
                                <?php for ($i=18;$i<=100;$i++):?>
                                    <option <?=($postData['pr_age_min'] == $i)?'selected':''?> value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>-&nbsp;&nbsp;

                            <select class="same_line_select" style="width: 100px!important;" name="pr_age_max" required>
                                <option <?= $selected ?> value="0">---</option>
                                <?php for ($i=18;$i<=100;$i++): ?>
                                    <option <?=($postData['pr_age_max'] == $i)?'selected':''?> value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Profession')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="pr_profession" required>
                                <option <?=($postData['pr_profession'] == 0)?'selected':''?> value="0"><?=$lng->get('Don\'t mind')?></option>
                                <option <?=($postData['pr_profession'] == 1)?'selected':''?> value="1"><?=$lng->get('Student')?></option>
                                <option <?=($postData['pr_profession'] == 2)?'selected':''?> value="2"><?=$lng->get('Professional')?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Smoking')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="pr_smoking" required>
                                <option <?=($postData['pr_smoking'] == 0)?'selected':''?> value="0"><?=$lng->get('Don\'t mind')?></option>
                                <option <?=($postData['pr_smoking'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                <option <?=($postData['pr_smoking'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label><?=$lng->get('Animals or Pets')?>:</label>
                        </div>
                        <div class="col-xs-6">
                            <select class="same_line_select" name="pr_animals" required>
                                <option <?=($postData['pr_animals'] == 0)?'selected':''?> value="0"><?=$lng->get('Don\'t mind')?></option>
                                <option <?=($postData['pr_animals'] == 1)?'selected':''?> value="1"><?=$lng->get('No')?></option>
                                <option <?=($postData['pr_animals'] == 2)?'selected':''?> value="2"><?=$lng->get('Yes')?></option>
                            </select>
                        </div>
                    </div>


                    <!--Ad details-->
                    <div class="col-sm-12">
                        <h1 class="title"><?=$lng->get('Ad details')?></h1>
                        <hr class="dark_purple"/>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label><?=$lng->get('Description')?>:</label>
                            <textarea rows="5" name="description" placeholder="Additional information about your ad (not necessary)"><?=$postData['description']?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <hr class="bottomOnly"/>

                            <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                            <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Post your ad')?></button>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="custom_block">
                        <h1 class="title"><?=$lng->get('Your info')?></h1>
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
                                        <div><label><?=$lng->get('Name')?>:</label> <?=$postData['first_name']?></div>
                                        <div><label><?=$lng->get('Age')?>:</label> <?=Date::yearToAge($postData['birth_year'])?></div>
                                        <div><label><?=$lng->get('Gender')?>:</label> <?=Features::getGender($postData['gender'])?></div>
                                        <div><label><?=$lng->get('Phone')?>:</label> <?=Format::phoneNumber($postData['country_code'],$postData['phone'])?></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
</main>