<?php
use Helpers\Csrf;
use Helpers\Date;
?>
        <div class="container small_width default">
            <div class="row paddingBottom40">
                <div class="col-md-12" style="display: none" id="register_tab">
                    <form action="" method="POST">
                        <input type="hidden" value="<?=Csrf::makeToken('_register')?>" name="csrf_token_register" />
                        <input id="redirect_url_register" type="hidden" name="redirect_url"/>
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('First name')?></label>
                                <input required name="first_name" type="text" value="<?=$postData['first_name']?>">
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('Last name')?></label>
                                <input name="last_name" type="text" value="<?=$postData['last_name']?>">
                            </div>
                        </div>
                        <label><?=$lng->get('E-mail')?></label>
                        <input required name="email" type="email" value="<?=$postData['email']?>">

                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('Gender')?></label>

                                <select required name="gender" required>
                                    <option <?=($postData['gender'] == 0)?'selected':''?> value="0"><?=$lng->get('Not selected')?></option>
                                    <option <?=($postData['gender'] == 1)?'selected':''?> value="1"><?=$lng->get('Male')?></option>
                                    <option <?=($postData['gender'] == 2)?'selected':''?> value="2"><?=$lng->get('Female')?></option>
                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('Country')?></label>
                                <select required name="country_code" required>
                                    <?php foreach ($countryList as $country_code=> $country_name): if($postData['country_code']==$country_code){$selected='selected';}else{$selected='';}?>
                                        <option <?=$selected?> value="<?=$country_code?>"><?=$country_name.' (+'.$country_code.')'?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('Phone number')?></label>
                                <input required name="phone" type="text" value="<?=$postData['phone']?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label><?=$lng->get('Birth month')?></label>
                                <select required name="birth_month" required>
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
                                <select required name="birth_day" required>
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
                                <select required name="birth_year" required>
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
                           <input required name="password" type="password">

                            <button class="btn default_button btn-lg btn-block" type="submit"><?=$lng->get('Register')?></button>
                    </form>

                    <div style="text-align: center;padding: 10px;"><?=$lng->get('If you have account, then')?></div>
                    <button id="login_button" class="btn btn_gray btn-lg btn-block" type="submit"><?=$lng->get('Login')?></button>

                </div>

                <div class="col-md-12 " id="login_tab">
                    <div class="fb_sign_in">
                        <a href="<?=$data['postData']['facebook_url']?>"><?=$lng->get('Continue with Facebook')?></a>
                    </div>
                    <div style="text-align: center;padding: 10px;"><?=$lng->get('Or')?>:</div>
                    <form action="" method="POST">
                        <input type="hidden" value="<?=Csrf::makeToken('_login')?>" name="csrf_token_login" />
                        <input id="redirect_url_login" type="hidden" name="redirect_url"/>

                        <label><?=$lng->get('Login or E-mail')?></label>
                        <input name="email" type="text" value="<?=$postData['email']?>"/>

                        <label><?=$lng->get('Password')?></label>
                        <input name="password" type="password"/>
                        <hr class="bottomOnly"/>
                        <button class="btn default_button btn-lg btn-block" name="submit" type="submit"><?=$lng->get('Login')?></button>
                    </form>
                    <form action="register<?=$postData['return']?>" method="POST">
                        <div style="text-align: center;padding: 10px;"><?=$lng->get('If you have no account, then')?>
                            <a id="register_button" style="cursor:pointer;"><?=$lng->get('Register')?></a></div>

                    </form>
                    <div class="google_sign_in" style="display: none">
                        <a href="<?=$data['postData']['google_client']->createAuthUrl()?>"><i class="fa fa-facebook-f"></i> <?=$lng->get('Continue with Google')?></a>
                    </div>
                </div>
            </div>
        </div>