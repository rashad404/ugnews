<?php

use Helpers\Session;
use Helpers\Url;
$user_id = Session::get('user_session_id');
if($user_id>0){
?>
    <div class="flash_notification"><?=Session::getFlash()?></div>
<header class="main-header">
    <!-- Sidebar toggle button-->
    <a href="<?php echo Url::to('partner/main/index');?>" class="sidebar-toggle offcanvas" data-toggle="offcanvas" role="button"><i class="fa fa-bars"></i>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="<?php echo Url::to('partner/main/index');?>">
            <div class="mainlogocenter">
                Landlord Panel
            </div>
        </a>
    </nav>
</header>
<!-- Menu list -->
<?php include 'main_menu.php'; ?>
<!-- Menu list end -->
<?php }?>