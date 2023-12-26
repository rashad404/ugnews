<?php

use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">

    <!--    Why Us?-->
    <div class="container paddingBottom20">
        <div class="row paddingBottom40">
            <div class="col-sm-12">


                <div class="row">

                    <div class="col-lg-12">
                        <div class="page_title paddingTop20 paddingBottom20">
                            <h2>
                                <?= $lng->get($data['cat_name']) . $lng->get('News') ?>
                            </h2>
                            <hr />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php include 'news_include.php'; ?>
                </div>

            </div>
        </div>
    </div>

</main>