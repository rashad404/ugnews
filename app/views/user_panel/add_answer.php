<?php
use Helpers\Csrf;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container">
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <?=$breadcrumbs?>
                </div>
            </div>
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <div class="paddingBottom40"><span style="font-weight: bold"><?=$lng->get('Search Text')?>:</span> <?=$searchInfo['query']?></div>
                    <?=$lng->get('Your Answer')?>:

                    <form action="" method="POST">
                        <input type="hidden" value="<?= Csrf::makeToken() ?>" name="csrf_token"/>
                        <textarea id="summernote" name="text"></textarea>
                        <button class="submitButton" type="submit"><?=$lng->get('Add')?></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>