<?php
use Helpers\Url;
use Helpers\Csrf;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("Contact us")?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row paddingBottom20">
                <div class="col-sm-6">
                    <div class="contacts custom_block">
                        <h4><?=$lng->get('Connect with us')?></h4>
                        <p><?=$lng->get('For support or any questions')?><br/>
                            <?=$lng->get('E-mail us at')?>: <span class="contact_email"><?=$_PARTNER['email']?>
                        </p><br/>
                        <h4><?=$lng->get('Do you need fast response? Call us')?></h4>
                        <p><?=$lng->get('Phone')?>: <?=$_PARTNER['phone']?></p><br/>
                        <h4><?= PROJECT_NAME?> <?=$lng->get('Address')?></h4>
                        <p><?=$_PARTNER['address']?></p><br/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="contacts default custom_block">
                        <form action="" method="POST">
                            <input type="hidden" value="<?= Csrf::makeToken() ?>" name="csrf_token"/>
                            <h4><?=$lng->get('Get in Touch')?></h4>
                            <h5><?=$lng->get('Please fill out the quick form and we will be in touch lightening speed.')?></h5><br/>
                            <p><input class="default" name="name" value="<?=$postData['name']?>" placeholder="<?=$lng->get('Full name')?>" type="text"></p>
                            <p><input class="default" name="phone" value="<?=$postData['phone']?>" placeholder="<?=$lng->get('Mobile')?>" type="text"></p>
                            <p><input class="default" name="email" value="<?=$postData['email']?>" placeholder="<?=$lng->get('E-mail')?>" type="text"></p>
                            <p><textarea class="default" placeholder="<?=$lng->get('Message')?>" name="message" id="" rows="3" ><?=$postData['message']?></textarea></p>
                            <p><button class="btn btn-primary btn-lg btn-block" name="send_submit" type="submit"><?=$lng->get('Send')?></button></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<div class="container-fluid">
    <div class="row">
        <iframe width="100%" height="400" frameborder="0" style="border:0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.29271493315!2d-118.3543741484988!3d34.062009880508114!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2b92203c5e7b5%3A0x2f8aff3f817f49b1!2s5670%20Wilshire!5e0!3m2!1sen!2sus!4v1572724464967!5m2!1sen!2sus" allowfullscreen></iframe>
    </div>
</div>