<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container" style="max-width: 900px!important;">
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <div class="help_title">
                        <h1 class=""><?=$lng->get("Make Your Voice Heard")?></h1>
                        <h2 class=""><?=$lng->get("Create your own news channel and make your voice heard by millions")?></h2>
                        <form action="login/partner+news+index">
                            <button type="submit"><?=$lng->get("Let's Start")?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid help_block_1">
            <div class="row ">
                <div class="col-sm-12 help_block_div_1">
                    <div class="help_block_text_1">
                        <?=$lng->get("Everyone can create News Channel in minutes and start to write news. It's free and unlimited.")?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid help_block_2">
            <div class="row ">
                <div class="col-sm-6 help_block_2_div_1">
                    <div class="help_block_2_text_1">
                        <h1 class="">Ug.news <?=$lng->get("is Social Network which is focusing on News")?></h1>
                    </div>
                </div>
                <div class="col-sm-6 help_block_2_div_2">
                    <div class="help_block_2_img">
                        <img src="<?=\Helpers\Url::templatePath()?>img/partner_logos/ug_news.svg" alt="logo"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearBoth"></div>
    </section>
</main>
