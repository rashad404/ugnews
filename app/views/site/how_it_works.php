<?php
use Helpers\Url;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("How it works?")?></h1>
                </div>
            </div>
        </div>
        <div class="container custom_block how_it_works">
            <div class="row paddingBottom40 paddingTop40">
                <div class="col-sm-6">
                    <img src="<?=Url::templatePath()?>img/laptop.png?9" alt="Find a home"/>
                </div>
                <div class="col-sm-6">
                    <div class="step_title">1. Find your best home</div>
                    <div class="step_text">Go to the Apartments page, find affordable home which meets your requirements. We have useful filter, which will help you to find your home easily.</div>
                </div>
            </div>
            <div class="row paddingBottom40 paddingTop40">
                <div class="col-sm-6 pull-right">
                    <img src="<?=Url::templatePath()?>img/rent.png" alt="Appointment"/>
                </div>
                <div class="col-sm-6">
                    <div class="step_title">2. Schedule Appointment For Showing</div>
                    <div class="step_text">After you found your home, go to the Schedule showing page and fill out necessary fields and submit. Our leasing agents will contact you as soon as possible.</div>
                </div>
            </div>
            <div class="row paddingBottom40 paddingTop40">
                <div class="col-sm-6">
                    <img src="<?=Url::templatePath()?>img/apt_key.png" alt="Sign lease"/>
                </div>
                <div class="col-sm-6">
                    <div class="step_title">3. Sign lease and get your keys</div>
                    <div class="step_text">We have flexible leasing terms from 1 to 12 months. Sign your lease and get your keys in same day. Enjoy all benefits of Ureb community.</div>
                </div>
            </div>
        </div><br/>
    </section>
</main>
