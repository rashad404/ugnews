<?php

use Helpers\Csrf;
use Helpers\Session;
use Helpers\Url;
use Models\CartModel;
use Models\MenusModel;
use Models\SiteModel;
use Models\CountryModel;
use Helpers\Format;
?>

<?php
ob_start();
MenusModel::buildMenuList($_PARTNER['id']);
$menuList = ob_get_contents();
ob_end_clean();

ob_start();
MenusModel::buildMenuListMobile($_PARTNER['id']);
$menuListMobile = ob_get_contents();
ob_end_clean();

$count_cart = CartModel::countItems();

$data['contacts'] = SiteModel::getContacts();
$region = '233';
?>

<div class="flash_notification"><?=Session::getFlash()?></div>

<div class="all_site"></div>
<div class="mobile_menu">
    <div class="mobile_menu_header">
        <div class="login-buttons-mobile">
        </div>
        <a href=""><img src="<?=Url::templatePath()?>/img/partner_logos/<?=$_PARTNER['header_logo']?>" alt="<?=PROJECT_NAME?> logo"/></a>
    </div>
    <div class="mobile_menu_body">
        <div class="sign_in_mob">
            <?php if($userId>0):?>
                <a href="user_panel/profile" style="float: left"><i class="fas fa-user-alt"></i> <?=$userInfo['first_name']?></a>
                <a style="float:right;" href="user_panel/logout"><i class="fas fa-sign-out"></i> <?=$lng->get('Logout')?></a>
            <?php else:?>
<!--                <a style="float:left;" href="login">login</a>-->
<!--                <a style="float: right;" href="login"><i class="fas fa-sign-in-alt"></i> register</a>-->
                <a style="float: left;" href="login"><i class="fas fa-sign-in-alt"></i> <?=$lng->get('Sign in')?></a>
            <?php endif;?>
        </div>
        <?php if($userId>0):?>
        <ul class="nav">
            <?php if($userInfo['landlord_portal']==1):?><li><a href="partner"><?=$lng->get('Admin Portal')?></a></li><?php endif;?>
            <?php if($userInfo['tenant_portal']==1):?><li><a href="user"> <?=$lng->get('User Portal')?></a></li><?php endif;?>
        </ul>
        <?php endif;?>
        <?= $menuListMobile;?>
    </div>
</div>



<div class="header-notifications remove_col_padding" style="display: none">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <i class="fa fa-phone"></i> <?=$data['contacts']['home_tel']?>
                <i class="fa fa-envelope"></i> <?=$data['contacts']['email']?>
            </div>
        </div>
    </div>
</div>

<div class="header">
    <div class="container-fluid">
        <div class="row">

            <div class="col-xs-7 col-sm-4">
                <div class="header_logo">
                    <div>
                        <a href="">
                            <img class="logo" src="<?=Url::templatePath()?>/img/partner_logos/<?=$_PARTNER['header_logo']?>" alt="<?=PROJECT_NAME?> logo"/>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xs-1 col-sm-5">

                <div class="search_area hidden-xs hidden-sm">
                    <form action="" method="POST" >
                        <input type="hidden" value="<?= Csrf::makeToken();?>" name="csrf_token">
                        <input class="" type="text" name="search" id="search" value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>" placeholder="<?=$lng->get('Channel or News')?>">

                        <button type="submit" class="">
                            <?=$lng->get('Search')?>
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                </div>
            </div>

            <div class="col-xs-4 col-sm-3">

                <div class="icons_area">
                    <ul class="menu">
                        <li class="menu_li">
                            <a class="login_info" href="javascript:void(0);"><i class="fas fa-globe"></i> <?=CountryModel::getCode($_SETTINGS['region'])?> <i class="fas fa-caret-down"></i></a>
                            <ul class="sub_menu" style="max-height: 500px;right: 0px;">
                                <li class="li_title"><?=$lng->get('Select Region')?>:</li>
                                <?php foreach (CountryModel::getList() as $country) :?>
                                    <li><a href="set/region/<?=$country['id']?>"><i class="fas fa-caret-right"></i> <?=$country['name']?></a></li>
                                <?php endforeach;?>
                            </ul>

                        </li>
                    </ul>
                </div>

                <div class="mobile_menu_icon">
                    <a href="javascript:void(0);" class="icon">
                        <i class="fa fa-bars fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
