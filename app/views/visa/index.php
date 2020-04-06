<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><i class="fa fa-id-card"></i> <?=$lng->get('Visa service')?></h3>

        </div>
        <div class="container">
            <div class="row paddingTop40 paddingBottom40">

                <div class="col-md-4">
                    <?php $selected_menu='index'; include 'menu.php'; ?>
                </div>

                <div class="col-md-8">
                    <div class="visa_content">
                        <div class="content_part">
                            <h5>Viza Dəstəyi nədir?</h5>
                            <p>Viza dəstəyi xidməti, sənədlərin düzgün yığılması üzrə ümumi konsultasiya, otel və aviabilet bronun təqdim olunması, ərizə formalarının doldurulması, səfirlikdə görüş gününün təyin olunmasını nəzərdə tutur.</p>
                        </div>
                        <div class="content_part">
                            <h5>Xidmətə nələr daxildir?</h5>
                            <p>
                                <ul>
                                    <li>Ümumi konsultasiya</li>
                                    <li>Blank doldurulması</li>
                                    <li>Sənədlərin düzgün hazırlanması</li>
                                    <li>Otel və bilet bronları</li>
                                    <li>Səfirliklə görüş tarixinin təyin edilməsi</li>
                                </ul>
                            </p>
                        </div>
                        <div class="content_part">
                            <h5>Xidmətə nələr daxil deyil?</h5>
                            <p>
                                <ul>
                                    <li>Səfirliyə ödəniləcək viza rüsumu</li>
                                    <li>Sığorta</li>
                                </ul>
                            </p>
                        </div>
                        <div class="content_part">
                            <h5>Xidmət haqqı nə qədərdir?</h5>
                            <p>Viza dəstəyi xidməti ilə əlaqədar şirkətimizə ödənilən xidmət haqqları aşağıdakı kimidir:
                                <ul>
                                    <li>Şengen – 19 AZN</li>
                                    <li>ABŞ – 59 AZN</li>
                                    <li>Birləşmiş Krallıq – 59 AZN</li>
                                    <li>Kanada – 59 AZN</li>
                                    <li>BƏƏ – 170 AZN (Viza rüsumu ilə birlikdə)</li>
                                    <li>Çin – 390 AZN (Viza rüsumu ilə birlikdə)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
