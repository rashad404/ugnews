<?php
use Helpers\Session;
use Helpers\Url;
?>

<div><?=Session::getFlash()?></div>
<div class="header">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href=""><img src="<?=Url::templatePath()?>img/logo.png" alt="<?=PROJECT_NAME?>"></a></a>
            </div>
            <ul class="nav navbar-nav">
                <?php foreach ($data['menus'] as $menu):
                    if($_SERVER['REQUEST_URI']==DIR.'search')$_SERVER['REQUEST_URI']=DIR;
                    if($_SERVER['REQUEST_URI']==DIR.$menu['url']){$active_menu = 'active';}else{$active_menu = '';}
                    ?>
                    <li class="<?=$active_menu?>"><a href="<?=$menu['url']?>"><?=$menu['title_'.$data['defLang']]?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="login-buttons">
                <a href="user/logout"><?=$lng->get('Logout')?></a>
            </div>
        </div>
    </nav>
</div>
