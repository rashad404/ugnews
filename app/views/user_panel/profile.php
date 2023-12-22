<?php
use Helpers\Csrf;
use Helpers\Date;
use Helpers\Url;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container small_width default">
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <h1 class="title"><?=$lng->get('Account info')?></h1>
                    <hr class=" "/>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                    <div class="col-sm-12">

                        <style>
                            /*Profile Pic Start*/
                            .picture-container{
                                position: relative;
                                cursor: pointer;
                                text-align: center;
                            }
                            .picture{
                                width: 106px;
                                height: 106px;
                                background-color: #999999;
                                border: 4px solid #CCCCCC;
                                color: #FFFFFF;
                                border-radius: 50%;
                                margin: 0px auto;
                                overflow: hidden;
                                transition: all 0.2s;
                                -webkit-transition: all 0.2s;
                            }
                            .picture:hover{
                                border-color: #2ca8ff;
                            }
                            .content.ct-profile-green .picture:hover{
                                border-color: #05ae0e;
                            }
                            .content.ct-profile-blue .picture:hover{
                                border-color: #3472f7;
                            }
                            .content.ct-profile-orange .picture:hover{
                                border-color: #ff9500;
                            }
                            .content.ct-profile-red .picture:hover{
                                border-color: #ff3b30;
                            }
                            .picture input[type="file"] {
                                cursor: pointer;
                                display: block;
                                height: 100%;
                                left: 0;
                                opacity: 0 !important;
                                position: absolute;
                                top: 0;
                                width: 100%;
                            }

                            .picture-src{
                                width: 100%;

                            }
                            /*Profile Pic End*/
                        </style>
                        <script>
                            $(document).ready(function(){
                                // Prepare the preview for profile picture
                                $("#profile-picture").change(function(){
                                    readURL(this);
                                });
                            });
                            function readURL(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

                                    reader.onload = function (e) {
                                        $('#profilePicturePreview').attr('src', e.target.result).fadeIn('slow');
                                    }
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                        <div class="picture-container">
                            <div class="picture">
                                <?php if (file_exists(Url::uploadPath() . 'users/' . $data['userId'] . '.jpg')): ?>
                                    <img src="<?= Url::uploadPath() . 'users/' . $data['userId'] ?>.jpg?ref=<?= rand(1111111, 9999999) ?>" class="picture-src" id="profilePicturePreview" title="">
                                <?php else: ?>
                                    <img src="<?= URL::templatePath() ?>/img/profile_photo-02.png" class="picture-src" id="profilePicturePreview" title="">
                                <?php endif; ?>
                                <input type="file" name="file" id="profile-picture" class="">
                            </div>
                            <h6 class=""><?=$lng->get('Choose Photo')?></h6>
                        </div>
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
                                    <input style="width: 100%;" name="phone" placeholder="<?=$lng->get('Phone')?>" type="text" value="<?=$postData['phone']?>">
                            </div>
                        </div>

                        <label><?=$lng->get('E-Mail')?></label>
                        <input disabled placeholder="<?=$lng->get('E-mail')?>" type="text" value="<?=$postData['email']?>">
                        <hr class="bottomOnly"/>
                        <button class="btn btn-primary btn-lg btn-block" type="submit"><?=$lng->get('Update')?></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>