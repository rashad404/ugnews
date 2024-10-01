<?php

use Helpers\Session;
use Helpers\Url;
use Models\MenusModel;
use Models\SiteModel;
use Models\CountryModel;
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


$data['contacts'] = SiteModel::getContacts();
$region = '233';
?>

<div class="flash_notification"><?=Session::getFlash()?></div>


<div class="all_site"></div>
<div class="all_site_no_bg"></div>
<div id="mobile_menu" class="mobile_menu mobile_menu_open">
    <div class="mobile_menu_body">
        <div class="sign_in_mob">
            <?php if($userId>0):?>
                <a href="/user_panel/profile" style="float: left"><i class="fas fa-user-alt"></i> <?=$userInfo['first_name']?></a>
                <a style="float:right;" href="/user_panel/logout"><i class="fas fa-sign-out"></i> <?=$lng->get('Logout')?></a>
            <?php else:?>
                <a style="float: left;" href="/login"><i class="fas fa-sign-in-alt"></i> <?=$lng->get('Sign in')?></a>
            <?php endif;?>
        </div>
        <?php include "app/templates/main/layouts/inc/left_menu.php";?>
    </div>
</div>


<div class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-7 col-sm-4 remove_col_padding_mob">

                <div class="header_logo">
                    <div>
                        <div class="mobile_menu_icon" style="float: left">
                            <a href="javascript:void(0);" class="icon">
                                <i class="fa fa-bars fa-2x"></i>
                            </a>
                        </div>
                        <a href="/">
                            <img class="logo" src="<?=Url::templatePath()?>/img/partner_logos/<?=$_PARTNER['header_logo_white']?>" alt="<?=PROJECT_NAME?> logo"/>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-0 col-sm-5">


                <div class="search_area hidden-xs hidden-sm">
                        <input class="" type="text" name="search" id="header_search_input" value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>" placeholder="<?=$lng->get('Channel or News')?>">
                        <button type="submit" class="">
                            <?=$lng->get('Search')?>
                            <i class="fas fa-search"></i>
                        </button>

                </div>
            </div>

            <div class="col-5 col-sm-3 ">


                <div class="icons_area" style="float: right">
                    <ul class="menu">
                        <li class="menu_li">
                            <a class="login_info" href="javascript:void(0);"><i class="fas fa-globe"></i> <span class="hidden-xs"><?=CountryModel::getCode($_SETTINGS['region'])?></span> <i class="fas fa-caret-down hidden-xs"></i></a>
                            <ul class="sub_menu" style="max-height: 500px;right: 0px;">
                                <li class="li_title"><?=$lng->get('Select Region')?>:</li>
                                <?php foreach (CountryModel::getList() as $country) :?>
                                    <li><a href="set/region/<?=$country['id']?>"><i class="fas fa-caret-right"></i> <?=$country['name']?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </li>
                    </ul>
                </div>

<!--                <div id="mobile_search_icon2" class="search_icon visible-xs" style="float: right">-->
<!--                    <a href="javascript:void(0);" class="icon">-->
<!--                        <i class="fa fa-search fa-2x"></i>-->
<!--                    </a>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</div>

<ul id="headerSearchDropDown" class="search_result_modal_box" style="max-height: 500px;right: 0px;">
    <li class="li_title"><?=$lng->get('Loading')?>...</li>
</ul>

<div id="headerSearchBoxMobile" class="search_box_mobile" style="max-height: 200px;right: 0px;">
        <input class="" type="text" name="search" id="header_search_input_mobile" value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>" placeholder="<?=$lng->get('Channel or News')?>">
        <button type="submit" class="">
            <?=$lng->get('Search')?>
            <i class="fas fa-search"></i>
        </button>
</div>

