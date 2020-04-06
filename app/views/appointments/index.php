<?php
    use Helpers\Csrf;
    use Helpers\Date;
?>
<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid inner_background">
                    <h1><?=$lng->get("Schedule an appointment")?></h1>
                </div>
            </div>
        </div>
        <div class="container default">
            <div class="row paddingBottom40">
                <div class="col-sm-12 custom_block">
                    <form action="" method="POST">
                        <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('First name')?></label>
                                <input name="first_name" type="text" value="<?=$postData['first_name']?>">
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('Last name')?></label>
                                <input name="last_name" type="text" value="<?=$postData['last_name']?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('Phone number')?></label>
                                <input name="phone" type="text" value="<?=$postData['phone']?>">
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('E-mail')?></label>
                                <input title="<?=$lng->get('E-mail')?>" name="email" type="text" value="<?=$postData['email']?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('When are you available?')?></label>
                                <div class="form-group default_date">
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type='text' name="app_date"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label><?=$lng->get('Desired move-in date')?></label>
                                <div class="form-group default_date">
                                    <div class='input-group date' id='datetimepicker2'>
                                        <input type='text' name="move_date"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><?=$lng->get('Do you have pets?')?></label>
                                <select name="pets" required title="<?=$lng->get('Do you have pets?')?>">
                                    <option value="0"><?=$lng->get('No')?></option>
                                    <option value="1"><?=$lng->get('Yes')?></option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><?=$lng->get('If you have any specifically notes please write down.')?></label>
                                <textarea name="note" title="<?=$lng->get('If you have any specifically notes please write down.')?>"></textarea>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-8">

                            </div>
                            <div class="col-md-4">
                                <button class="btn default_button btn-lg btn-block" type="submit"><?=$lng->get('Submit')?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</main>





<script>
    $(document).ready(function() {
        $(function() {
            $('#datetimepicker1').datetimepicker();
            $('#datetimepicker2').datetimepicker();
        });
    });
</script>
