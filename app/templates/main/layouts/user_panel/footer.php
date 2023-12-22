<?php
use Helpers\Url;
?>
<footer class="footer _background-dark-blue">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-12">
                <div class="footer__block">
                    <p class="footer__paragraph text-uppercase">© <?=date("Y")?> <?=PROJECT_NAME?> <?=$lng->get('LLC')?></p>
                    <p class="footer__paragraph"> <?=$lng->get('All rights reserved')?></p>
                </div>
            </div>

            <div class="col-sm-6 col-12">
                <div class="footer__block text-right">

                </div>
            </div>
        </div>
    </div>
</footer>