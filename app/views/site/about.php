<?php
use Helpers\Url;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("Who we are?")?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row paddingBottom40">
                <div class="col-sm-12 custom_block">
                    <div class="about_text"><?=html_entity_decode($item['text_'.$data['def_language']])?></div>
                </div>
            </div>

        </div>
    </section>
</main>
