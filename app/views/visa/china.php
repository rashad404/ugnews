<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><i class="fa fa-id-card"></i> <?=$lng->get('China visa')?> <?=$lng->get('support')?></h3>

        </div>
        <div class="container">
            <div class="row paddingTop40 paddingBottom40">

                <div class="col-md-4">
                    <?php $selected_menu='china'; include 'menu.php'; ?>
                </div>

                <div class="col-md-8">
                    <div class="visa_content">
                        <div class="content_part">
                            <h5>Viza üçün tələb olunan sənədlər</h5>
                            <p>
                                <ul class="digit_style">
                                    <li><span>Xarici passport</span></li>
                                    <li><span>Viza rüsumunun ödənilməsini təsdiq edən sənəd</span></li>
                                    <li><span>Gediş-gəliş biletinin bronu</span></li>
                                    <li><span>İş yerindən elektron arayış</span></li>
                                    <li><span>VÖEN</span></li>
                                    <li><span>Bank hesabında 20.000 USD və ya evin kupçası (Hüquqların dövlət qeydiyyatı haqqında daşınmaz əmlakın dövlət reyestrindən çıxarışı)</span></li>
                                    <li><span>Mehmanxana sifarişinin təsdiqi/Dəvət məktubu</span></li>
                                    <li><span>Şəkil 3 sm x 4.5 sm</span></li>
                                    <li><span>Doldurulmuş ərizə formaları</span></li>
                                </ul>
                            </p>
                        </div>
                        <div class="content_part">
                            <h5>Əməliyyat müddəti</h5>
                            <p>Sizin müraciətinizin cavabı və ya sizə viza verilmə prosesi 3-7 iş günü müddətində olur.</p>
                        </div>
                        <div class="content_part">
                            <h5>Xüsusi qeydlər</h5>
                            <ul>
                                <li>Fly.az şirkəti turistlərə vizanın verilməsinə tam zəmanət vermir.</li>
                                <li>Vizanın verilməsi bir başa Konsulluq xidmətinin ixtiyarındadır.</li>
                                <li>Vizanın verilməsi və ya imtina ediməsi konsulluq tərəfindən qərara alınır.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
