<?php
use Helpers\Url;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("Locations")?></h1>
                    <h4><?=$lng->get("Where do you want to stay?")?></h4>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row custom_block paddingBottom40 paddingTop40">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="location_box">
                        <a href="apartments/downtown-la" title="Downtown LA Apartments">
                            <div class="location_image"><img src="<?=Url::uploadPath()?>locations/downtown_la.jpg" alt="Downtown LA"/></div>
                            <div class="location_text" style="background-color: #316cc6;">
                                <div class="location_title">Downtown LA</div>
                                <div class="location_city" style="color: #b7d5f9;">Los Angeles</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="location_box">
                        <a href="apartments/koreatown" title="Koreatown Apartments">
                            <div class="location_image"><img src="<?=Url::uploadPath()?>locations/koreatown.jpg" alt="Downtown LA"/></div>
                            <div class="location_text" style="background-color: #315f22;">
                                <div class="location_title">Koreatown</div>
                                <div class="location_city" style="color: #97bf8a;">Los Angeles</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="location_box">
                        <a href="apartments/santa-monica" title="Santa Monica Apartments">
                            <div class="location_image">
                                <img src="<?=Url::uploadPath()?>locations/santa_monica.jpg" alt="Santa Monica"/>
                            </div>
                            <div class="location_text" style="background-color: #F1689D;">
                                <div class="location_title">Santa Monica</div>
                                <div class="location_city" style="color: #f8d8e5;">Los Angeles</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
