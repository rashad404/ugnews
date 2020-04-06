<?php
use Helpers\Csrf;
?>
<div class="loginbox">
    <div class="lbad"></div>
    <div class="lbad2"></div>
    <div class="container contbox">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel pad-top-50">
                    <div class="panel-heading text-center">
                        <span style="font-size:26px;color:#5cc35c;">LOGO</span>

                    </div>
                    <div class="panel-body">
                        <?= \Helpers\Session::getFlash();?><br />
                        <form action="" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control admininput" placeholder="Login" name="login" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control admininput" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                                <input type="submit" class="btn secimetbtnadd btncolor pull-left mar-top-7" value="Login" style="margin-top: 7px;" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footerboxlogin">
            <div class="footertext1">
                <img src="<?= \Helpers\Url::templateModulePath() ?>icons/saytaz.png" >
            </div>
            <span class="footertext2">&copy; Bütün hüquqlar qorunur</span>
        </div>
    </div>
</div>
