<?php
use \Helpers\Url;
use Models\LanguagesModel;
$params = $data['params'];
$partner_info = $data['partner_info'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
<script type="text/javascript" src="<?=Url::templateUserPath()?>square/sqpaymentform<?=$partner_info['id']?>.js"></script>
<link rel="stylesheet" type="text/css" href="<?=Url::templateUserPath()?>square/sqpaymentform-basic.css?<?=UPDATE_VERSION?>">
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        if (SqPaymentForm.isSupportedBrowser()) {
            paymentForm.build();
            paymentForm.recalculateSize();
        }
    });
</script>

<section class="content-header">
    <div class="headtext">
        <span><?= $params["title"]; ?></span>
    </div>
</section>

<section class="content">

                            <div id="form-container">
                                <div id="sq-ccbox">
                                    <!--
                                      Be sure to replace the action attribute of the form with the path of
                                      the Transaction API charge endpoint URL you want to POST the nonce to
                                      (for example, "/process-card")
                                    -->
                                    <form id="nonce-form" novalidate action="" method="post">
                                        <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                                        <fieldset>
                                            <span class="label">Card Number</span>
                                            <div id="sq-card-number"></div>

                                            <div class="third">
                                                <span class="label">Expiration</span>
                                                <div id="sq-expiration-date"></div>
                                            </div>

                                            <div class="third">
                                                <span class="label">CVV</span>
                                                <div id="sq-cvv"></div>
                                            </div>

                                            <div class="third">
                                                <span class="label">ZIP CODE</span>
                                                <div id="sq-postal-code"></div>
                                            </div>

                                                <span class="label">Amount (USD)</span>
                                                <input class="amount" type="text" id="amount" name="amount" placeholder="100.00">

                                        </fieldset>
                                        <div class="note">
                                            <span style="color:red;">Note:</span> You will be charged +3% for convenience fee.
                                        </div>

                                        <button id="sq-creditcard" class="button-credit-card" onclick="requestCardNonce(event)">Pay</button>

                                        <div id="error"></div>

                                        <!--
                                          After a nonce is generated it will be assigned to this hidden input field.
                                        -->
                                        <input type="hidden" id="card-nonce" name="nonce">
                                    </form>
                                </div> <!-- end #sq-ccbox -->

                            </div> <!-- end #form-container -->


</section><!-- /.content -->
