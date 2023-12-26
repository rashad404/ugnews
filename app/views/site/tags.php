<?php

use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;

$lowerCat = strtolower($data['cat_name']);
?>
<main class="main">
    <!--    Why Us?-->
    <div class="container paddingBottom20">
        <div class="row paddingBottom40">
            <div class="col-sm-12">
                <div class="row ">
                    <?php if ($lowerCat == "valyuta") : ?>
                        <div class="col-lg-6">
                            <div class="page_title paddingTop20 paddingBottom20">
                                <h2>
                                    AZN Məzənnələri
                                </h2>
                                <hr />
                            </div>
                            <table class="w-100" id="valyuta-table">
                            </table>
                        </div>
                    <?php endif; ?>
                    <div class=<?php echo $lowerCat == 'valyuta' ? "col-lg-6" : '' ?>>
                        <div class="weather">
                            <h2>Hava haqqında məlumat</h2>
                            <?php include "weather_inner.php"; ?>
                        </div>
                        <div class="page_title paddingTop20 paddingBottom20">
                            <h2>
                                <?= $lng->get($data['cat_name']) . $lng->get(' News') ?>
                            </h2>
                            <hr />
                        </div>

                        <div class=<?php echo $lowerCat == 'valyuta' ? "d-flex flex-column" : 'row' ?>>
                            <?php $cat_name = $data['cat_name'];
                            include 'news_include.php'; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</main>