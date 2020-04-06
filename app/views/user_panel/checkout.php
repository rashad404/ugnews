<?php
use Helpers\Csrf;
use Helpers\Date;
use Helpers\Format;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container medium_width default">
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <h1 class="title"><?=$lng->get('Checkout')?></h1>
                    <hr/>
                </div>


                <form action="" method="POST">
                    <input class="default" type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                    <div class="col-md-4 col-md-push-8">
                        <div class="checkout_cart_box">
                            <div class="checkout_cart_box_content">
                                <div class="checkout_cart_box_title">
                                    <div class="pull-left">
                                        <?=$lng->get('Your cart')?>
                                    </div>
                                    <div class="pull-right">
                                        <a href="cart"><?=$lng->get('Edit')?></a>
                                    </div>
                                    <div class="clearBoth"></div>
                                </div>
                                <?php foreach ($cart_list as $cart):?>
                                    <div class="checkout_cart_product">
                                        <div class="pull-left">
                                            <?=$cart['count']?> x <span class="checkout_cart_product_name"><?=Format::listTitle($cart['title_'.$def_language],17)?></span><br/>
                                            <small class="text-muted"> <?=Format::listText($cart['text_'.$def_language])?></small>
                                        </div>
                                        <div class="pull-right"><?=DEFAULT_CURRENCY?><?=$cart['price']?></div>
                                        <div class="clearBoth"></div>
                                    </div>
                                <?php endforeach;?>
                                <div class="checkout_cart_product">
                                    <div class="pull-left">
                                        <span class="checkout_cart_product_name">Total</span>
                                    </div>
                                    <div class="pull-right"><span style="font-weight: bold"><?=DEFAULT_CURRENCY?><?=$cart_total?></span></div>
                                    <div class="clearBoth"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-md-pull-4">
                        <div class="checkout_box">
                            <div class="checkout_box_title">
                                    <div class="pull-left">
                                        1. <?=$lng->get('Shipping')?>
                                    </div>
                                    <div class="pull-right">
                                        <a href="user/address"><?=$lng->get('Edit')?></a>
                                    </div>
                                <div class="clearBoth"></div>
                            </div>
                            <div class="checkout_box_content">
                                <div><label><?=$address_info['first_name']?> <?=$address_info['last_name']?></label></div>
                                <div><?=$address_info['street']?> <?=$address_info['apt']?></div>
                                <div><?=$address_info['city']?>, <?=$address_info['state']?> <?=$address_info['zip']?></div>
                            </div>
                            <hr class="light_gray"/>
                            <div class="checkout_box_title">
                                <div>
                                    2. <?=$lng->get('Payment')?>
                                </div>
                            </div>
                            <div class="checkout_box_content">
                                <div class="default_radio">
                                    <input class="payment_type" type="radio" name="payment_type" id="cash" value="cash" checked /> <label for="cash"><?=$lng->get('Cash')?></label>
                                </div>
                                <div class="checkout_payment_content" id="cash_content">
                                    <span><?=$lng->get('If you choose Cash, you will pay when you receive items. No action is required now')?></span>
                                </div>
                                <div class="default_radio">
                                    <input class="payment_type" type="radio" name="payment_type" id="card" value="card"/> <label for="card"><?=$lng->get('Credit/Debit card')?></label>
                                </div>
                                <div class="checkout_payment_content hidden" id="card_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label><?=$lng->get('Name on card')?></label>
                                            <input name="card_name" type="text" value="<?=$postData['card_name']?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label><?=$lng->get('Credit card number')?></label>
                                            <input name="card_number" type="text" value="<?=$postData['card_number']?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-md-3">
                                                <label><?=$lng->get('Expiration')?></label>
                                                <input name="card_exp" type="text" value="<?=$postData['card_exp']?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label><?=$lng->get('CVV')?></label>
                                                <input name="card_cvv" type="text" value="<?=$postData['card_cvv']?>">
                                            </div>
                                        <div class="col-md-6">
                                        </div>
                                    </div>
                                </div>
                                <div class="default_radio">
                                    <input type="radio" class="payment_type" name="payment_type" id="paypal" value="paypal"/> <label for="paypal"><?=$lng->get('Paypal')?></label>
                                </div>

                                <div class="checkout_payment_content hidden" id="paypal_content">
                                    <span><?=$lng->get('If you choose Paypal, you will be redirected to Paypal website to make payment')?></span>
                                </div>
                            </div>
                            <hr class="light_gray"/>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"><?=$lng->get('Place your order')?></button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </section>
</main>