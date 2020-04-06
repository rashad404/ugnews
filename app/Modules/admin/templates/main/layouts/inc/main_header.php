<?php
use Helpers\Url;
?>
<header class="main-header">
    <!-- Sidebar toggle button-->
    <a href="<?php echo Url::to(MODULE_ADMIN.'/main/index');?>" class="sidebar-toggle offcanvas" data-toggle="offcanvas" role="button"><i class="fa fa-bars"></i>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="<?php echo Url::to(MODULE_ADMIN.'/main/index');?>">
            <div class="mianlogocenter">
                <?=MODULE_ADMIN_TITLE?>
            </div>
        </a>
        <div class="navbar-custom-menu">
            <a><?= \Helpers\Session::getFlash(); ?></a>
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu pointer admincaretbtnbox">
                    <a class="dropdown-toggle pointer admincaretbtn" data-toggle="dropdown">
                        <span>Admin<i class="fa fa-caret-down pull-left"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header admininbtn">
                            <a href="<?= \Helpers\Url::to('admin/contacts/create')?>">Change info</a>
                        </li>
                        <li class="user-header admininbtn">
                            <a href="<?= \Helpers\Url::to('admin/main/logout')?>">Exit</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- Menu list -->
<?php include 'main_menu.php'; ?>
<!-- Menu list end -->