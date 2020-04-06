<?php
use Helpers\Csrf;
use Helpers\Date;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container small_width default">
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <h1 class="title"><?=$lng->get('Registration')?></h1>
                    <hr class="dark_gray"/>
                </div>
                <div class="col-md-12">
                    <form action="" method="POST">
                        <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('First name')?></label>
                                <input name="first_name" type="text" value="<?=$postData['first_name']?>">
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('Last name')?></label>
                                <input name="last_name" type="text" value="<?=$postData['last_name']?>">
                            </div>
                        </div>
                        <label><?=$lng->get('E-mail')?></label>
                        <input name="email" type="text" value="<?=$postData['email']?>">

                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('Gender')?></label>

                                <select name="gender" required>
                                    <option <?=($postData['gender'] == 0)?'selected':''?> value="0"><?=$lng->get('Not selected')?></option>
                                    <option <?=($postData['gender'] == 1)?'selected':''?> value="1"><?=$lng->get('Male')?></option>
                                    <option <?=($postData['gender'] == 2)?'selected':''?> value="2"><?=$lng->get('Female')?></option>
                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('Country')?></label>
                                <select name="country_code" required>
                                    <?php foreach ($countryList as $country_code=> $country_name): if($postData['country_code']==$country_code){$selected='selected';}else{$selected='';}?>
                                        <option <?=$selected?> value="<?=$country_code?>"><?=$country_name.' (+'.$country_code.')'?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('Phone number')?></label>
                                <input name="phone" type="text" value="<?=$postData['phone']?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label><?=$lng->get('Birth month')?></label>
                                <select name="birth_month" required>
                                    <?php foreach (Date::getMonths3Code() as $month => $month_name): if ($postData['birth_month'] == $month) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    } ?>
                                        <option <?= $selected ?>
                                                value="<?= $month ?>"><?= $lng->get($month_name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label><?=$lng->get('Day')?></label>
                                <select name="birth_day" required>
                                    <?php foreach (Date::getDays() as $day): if ($postData['birth_day'] == $day) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    } ?>
                                        <option <?= $selected ?> value="<?= $day ?>"><?= $day ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label><?=$lng->get('Year')?></label>
                                <select name="birth_year" required>
                                    <?php $max_years = date("Y")-16; foreach (Date::getYears(1950,$max_years) as $year): if ($postData['birth_year'] == $year) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    } ?>
                                        <option <?= $selected ?> value="<?= $year ?>"><?= $year ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <label><?=$lng->get('Password')?></label>
                           <input name="password" type="password">

                            <button class="btn default_button btn-lg btn-block" type="submit"><?=$lng->get('Register')?></button>
                    </form>
                    <form action="login<?=$postData['return']?>" method="POST">
                        <div style="text-align: center;padding: 10px;"><?=$lng->get('If you have account, then')?></div>
                        <button class="btn btn_gray btn-lg btn-block" type="submit"><?=$lng->get('Login')?></button>
                    </form>
                    </div>
            </div>
        </div>
    </section>
</main>