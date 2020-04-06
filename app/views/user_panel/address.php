<?php
use Helpers\Csrf;
use Helpers\Date;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container small_width default">
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <h1 class="title"><?=$lng->get('Shipping address')?></h1>
                    <hr class=" "/>
                </div>
                <form action="" method="POST">
                    <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                    <div class="col-sm-12">

                        <label><?=$lng->get('Country')?></label>
                        <select name="country_code" required>
                            <?php foreach ($countryList as $country_code=> $country_name): if($postData['country_code']==$country_code){$selected='selected';}else{$selected='';}?>
                                <option <?=$selected?> value="<?=$country_code?>"><?=$country_name.' (+'.$country_code.')'?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('First name')?></label>
                                <input name="first_name" type="text" value="<?=$postData['first_name']?>">
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('Last name')?></label>
                                <input name="last_name" placeholder="" type="text" value="<?=$postData['last_name']?>">
                            </div>
                        </div>

                        <label><?=$lng->get('Phone number')?></label>
                        <input style="width: 100%;" name="phone" placeholder="<?=$lng->get('Phone')?>" type="text" value="<?=$postData['phone']?>">

                        <label><?=$lng->get('Street and number')?></label>
                        <input name="street" type="text" value="<?=$postData['street']?>">

                        <label><?=$lng->get('Apartment, suite, unit, building, floor, etc.')?></label>
                        <input name="apt" type="text" value="<?=$postData['apt']?>">

                        <div class="row">
                            <div class="col-md-4">
                                <label><?=$lng->get('City')?></label>
                                <input name="city" placeholder="<?=$lng->get('City')?>" type="text" value="<?=$postData['city']?>">
                            </div>
                            <div class="col-md-4">
                                <label><?=$lng->get('State / Province')?></label>
                                <input name="state" type="text" value="<?=$postData['state']?>">
                            </div>
                            <div class="col-md-4">
                                <label><?=$lng->get('Zip code')?></label>
                                <input name="zip" type="text" value="<?=$postData['zip']?>">
                            </div>
                        </div>
                        <label><?=$lng->get('Phone number')?></label>
                        <input name="phone" type="text" value="<?=$postData['phone']?>">

                        <label><?=$lng->get('Additional info')?></label>
                        <textarea name="info"><?=$postData['info']?></textarea>
                        <hr class="bottomOnly"/>
                        <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Update')?></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>