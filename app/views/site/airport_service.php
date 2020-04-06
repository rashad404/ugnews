<?php
use Models\TextsModel;
use Helpers\Url;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("Airport Service")?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row paddingBottom40">
                <div class="col-sm-7 custom_block">
                    <div class="about_text">
                        <?=html_entity_decode(TextsModel::getText(10, 'Airport Service'))?>
                    </div>
                </div>
                <div class="col-sm-5 about_img">
                    <div class="rectangle">
                        <img style="width: 100%" src="<?=Url::templatePath()?>/img/lax.jpg" alt="Airport Service LAX"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
