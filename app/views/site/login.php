<?php
use Helpers\Csrf;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">

        <div class="container default paddingBottom40">
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6 custom_block">
                    <h1 class="title"><?=$lng->get('Sign in')?></h1>
                    <hr class="dark_gray"/>

                    <div class="fb_sign_in">
                        <a href="<?=$data['postData']['facebook_url']?>"><?=$lng->get('Continue with Facebook')?></a>
                    </div>
                    <div style="text-align: center;padding: 10px;"><?=$lng->get('Or')?>:</div>

                    <form action="" method="POST">
                        <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />

                        <label><?=$lng->get('Login or E-mail')?></label>
                        <input name="email" type="text" value="<?=$postData['email']?>"/>

                        <label><?=$lng->get('Password')?></label>
                        <input name="password" type="password"/>
                        <hr class="bottomOnly"/>
                        <button class="btn default_button btn-lg btn-block" name="submit" type="submit"><?=$lng->get('Login')?></button>
                    </form>
                    <form action="register<?=$postData['return']?>" method="POST">
                        <div style="text-align: center;padding: 10px;"><?=$lng->get('If you have no account, then')?>
                            <a href="register"><?=$lng->get('Register')?></a></div>

                    </form>
                    <div class="google_sign_in" style="display: none;">
                        <a href="<?=$data['postData']['google_client']->createAuthUrl()?>"><i class="fa fa-facebook-f"></i> <?=$lng->get('Continue with Google')?></a>
                    </div>
                </div>
                <div class="col-sm-3">
                </div>
            </div>
        </div>
    </section>
</main>