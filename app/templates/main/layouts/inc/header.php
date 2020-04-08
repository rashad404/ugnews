<?php
use Helpers\Session;
use Helpers\Url;
use Models\CartModel;
use Models\MenusModel;
use Models\SiteModel;
use Models\MessagesModel;
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
    <div class="mobile_menu_icon">
        <a href="javascript:void(0);" class="icon">
            <i class="fa fa-bars fa-2x"></i>
        </a>
    </div>
    <div class="header_logo">
        <div>
            <a href="">
                <img class="logo" src="<?=Url::templatePath()?>/img/partner_logos/<?=$_PARTNER['header_logo']?>" alt="<?=PROJECT_NAME?> logo"/>
            </a>
        </div>
    </div>
    <div class="menu hidden-xs">
        <?=$menuList?>
    </div>
    <?php if($userId>0):
        $new_messages = MessagesModel::countNewMessages();
    if($new_messages==0)$new_messages='';
        ?>
    <div class="icons_area">
        <ul>
            <li><a href="messages"><i class="fa fa-globe"></i>
                    <span class="new_message_header"><?=$new_messages?></span>
                </a></li>
            <li><a href=""><i class="fa fa-bell"></i></a></li>
        </ul>
    </div>
    <?php endif;?>
    <div class="account_area visible-lg">
        <ul class="menu">
            <li class="menu_li">
                <?php if($userId>0):?>
                <a class="login_info" href="javascript:void(0);"><i class="fas fa-user"></i> <?=Format::listTitle($userInfo['first_name'],5)?> <i class="fas fa-caret-down"></i></a>
                    <ul class="sub_menu">
                        <?php if($userInfo['landlord_portal']==1):?><li><a href="partner"><i class="fas fa-caret-right"></i>  <?=$lng->get('Admin Portal')?></a></li><?php endif;?>
                        <?php if($userInfo['tenant_portal']==1):?><li><a href="user"><i class="fas fa-caret-right"></i>  <?=$lng->get('User Portal')?></a></li><?php endif;?>
                        <li><a href="user_panel/profile"><i class="fas fa-caret-right"></i>  <?=$lng->get('Your Profile')?></a></li>
                        <li><a href="user_panel/logout"><i class="fas fa-caret-right"></i> <?=$lng->get('Logout')?></a></li>
                    </ul>
                <?php else:?>
                    <a class="login_info" href="login"><i class="fas fa-sign-in-alt"></i> <?=$lng->get('Sign in')?></a>
                <?php endif;?>
            </li>
        </ul>
    </div>
</div>
