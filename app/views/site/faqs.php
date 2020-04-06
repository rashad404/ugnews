<?php
use Models\TextsModel;
list($array1, $array2) = array_chunk($data['list'], ceil(count($data['list']) / 2));
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("Frequently Asked Questions | UREB")?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row paddingTop40 paddingBottom40">
                <div class="col-sm-6">
                    <?php foreach ($array1 as $item):?>
                        <div class="faq_box" id="box_<?=$item['id']?>">
                            <div class="faq_question" id="<?=$item['id']?>">
                                <span><?=$item['question']?></span>
                                <span style="float: right"><i class="fa fa-chevron-circle-down faq"></i></span>
                            </div>
                            <div class="faq_answer" id="faq_answer_<?=$item['id']?>">
                                <?=html_entity_decode($item['answer'])?>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="col-sm-6">
                    <?php foreach ($array2 as $item):?>
                        <div class="faq_box" id="box_<?=$item['id']?>">
                            <div class="faq_question" id="<?=$item['id']?>">
                                <span><?=$item['question']?></span>
                                <span style="float: right"><i class="fa fa-chevron-circle-down faq"></i></span>
                            </div>
                            <div class="faq_answer" id="faq_answer_<?=$item['id']?>">
                                <?=html_entity_decode($item['answer'])?>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>

        </div>
    </section>
</main>
<script>
    $(function() {
        $(".faq_question").click(function() {
            var this_id = $(this).attr('id');

            //set defaults
            $(".faq_answer").not("#faq_answer_"+this_id).hide();
            $(".faq_question").css("color", "#2f2f2f");
            $("i.faq").attr('class', 'fa fa-chevron-circle-down faq');

            //toggle
            if($("#box_"+this_id).hasClass("active")){
                $("#box_"+this_id).removeClass("active");
                $("#"+this_id).css("color", "#2f2f2f").find('i').attr('class', 'fa fa-chevron-circle-down faq');
            }else{
                $(".faq_box").removeClass("active");
                $("#box_"+this_id).addClass("active");
                $("#"+this_id).css("color", "#2ba4b3").find('i').attr('class', 'fa fa-times-circle faq');
            }
            $("#faq_answer_"+this_id).toggle();

        })
    });
</script>