<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><i class="fa fa-id-card"></i> <?=$lng->get('UAE visa')?> <?=$lng->get('support')?></h3>

        </div>
        <div class="container">
            <div class="row paddingTop40 paddingBottom40">

                <div class="col-md-4">
                    <?php $selected_menu='uae'; include 'menu.php'; ?>
                </div>

                <div class="col-md-8">
                    <div class="visa_content">
                        <div class="content_part">
                            <h5>Viza üçün tələb olunan sənədlər</h5>
                            <p>
                                <ul class="digit_style">
                                    <li><span>Xarici passport</span></li>
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
